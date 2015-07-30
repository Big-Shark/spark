var registrationScreen = new Vue({
    el: '#spark-register-screen',

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function() {
        this.getPlans();

        Stripe.setPublishableKey(STRIPE_KEY);

        queryString = URI(document.URL).query(true);

        if (queryString.coupon) {
            this.getCoupon(queryString.coupon);
        }
    },


    /*
     * Initial state of the component's data.
     */
    data: {
        plans: [],
        selectedPlan: null,
        planTypeState: false,
        currentCoupon: null,

        registerForm: {
            name: '', email: '', password: '', password_confirmation: '',
            plan: '', terms: false, coupon: null,
            stripe_token: null, errors: [], registering: false
        },

        cardForm: {
            number: '', cvc: '', month: '', year: '', zip: '', errors: []
        }
    },


    computed: {
        /*
         * Determine if the plans have been loaded from the API.
         */
        plansAreLoaded: function() {
            return this.plans.length > 0;
        },


        /*
         * Get all of the "default" available plans. Typically this is monthly.
         */
        defaultPlans: function () {
            if (this.monthlyPlans.length > 0) {
                return this.monthlyPlans;
            }

            if (this.yearlyPlans.length > 0) {
                return this.yearlyPlans;
            }
        },


        /*
         * Get all of the plans that have a mnthly interval.
         */
        monthlyPlans: function() {
            return _.filter(this.plans, function(plan) {
                return plan.interval == 'monthly' && plan.active;
            });
        },


        /*
         * Get all of the plans that have a yearly interval.
         */
        yearlyPlans: function() {
            return _.filter(this.plans, function(plan) {
                return plan.interval == 'yearly' && plan.active;
            });
        },


        /*
         * Determine if a free plan is currently selected during registration.
         */
        freePlanIsSelected: function() {
            return this.selectedPlan && this.selectedPlan.price === 0;
        },


        /*
         * Determine if the plan type selector is set to "monthly".
         */
        shouldShowDefaultPlans: function () {
            return ! this.planTypeState;
        },


        /*
         * Determine if the plan type selector is set to "yearly".
         */
        shouldShowYearlyPlans: function () {
            return this.planTypeState;
        },


        /*
         * Get the full selected plan price with currency symbol.
         */
        selectedPlanPrice: function () {
            if (this.selectedPlan) {
                return this.selectedPlan.currencySymbol + this.selectedPlan.price;
            }
        },


        /*
         * Get the displayable discount for the current coupon.
         */
        currentCouponDisplayDiscount: function () {
            if (this.currentCoupon) {
                if (this.currentCoupon.amountOff) {
                    return '$' + this.currentCoupon.amountOff + ' Off';
                }

                if (this.currentCoupon.percentOff) {
                    return this.currentCoupon.percentOff + '% Off';
                }
            }
        },
    },


    methods: {
        /*
         * Get all of the Spark plans from the API.
         */
        getPlans: function() {
            this.$http.get('spark/api/plans')
                .success(function(plans) {
                    this.plans = _.filter(plans, function(plan) {
                        return plan.active;
                    });

                    // If there is only one plan, automatically select it...
                    if (this.plans.length == 1) {
                        this.setSelectedPlan(this.plans[0]);

                        setTimeout(function () {
                            $('.spark-first-field').filter(':visible:first').focus();
                        }, 100);
                    }
                });
        },


        /*
         * Get the specified coupon for registration.
         */
        getCoupon: function (coupon) {
            this.$http.get('spark/api/coupon/' + coupon)
                .success(function (coupon) {
                    this.currentCoupon = coupon;
                })
                .error(function (errors) {
                    console.error('Unable to load coupon for given code.');
                });
        },


        /*
         * Calculate the discounted price for plan based on current coupon.
         */
        getDiscountPlanPrice: function (price) {
            if (this.currentCoupon) {
                if (this.currentCoupon.amountOff) {
                    price = price - this.currentCoupon.amountOff;
                }

                if (this.currentCoupon.percentOff) {
                    price = price - (price * (this.currentCoupon.percentOff / 100));
                }

                if (price < 0) {
                    return 0;
                }

                return price.toFixed(2);
            }
        },


        /*
         * Get the displayable discount duration for the current coupon.
         */
        getCouponDisplayDuration: function (plan) {
            if (this.currentCoupon) {
                if (this.currentCoupon.lastsOnce) {
                    if (plan.interval == 'monthly') {
                        return 'For The First Month';
                    } else {
                        return 'For The First Year';
                    }
                }

                if (this.currentCoupon.lastsForever) {
                    if (plan.interval == 'monthly') {
                        return 'Monthly For Life';
                    } else {
                        return 'Yearly For Life';
                    }
                }

                if (this.currentCoupon.months) {
                    if (plan.interval == 'monthly') {
                        return 'Monthly For The First ' + this.currentCoupon.months + ' Months';
                    } else {
                        return 'For The First Year';
                    }
                }
            }
        },


        /*
         * Select a plan from the list. Initialize registration form.
         */
        selectPlan: function(plan) {
            this.setSelectedPlan(plan);

            setTimeout(function() {
                $('.spark-first-field')
                        .filter(':visible:first')
                        .focus();
            }, 100);
        },


        /*
         * Set the selected plan.
         */
        setSelectedPlan: function(plan) {
            this.selectedPlan = plan;
            this.registerForm.plan = plan.id;
        },


        /*
         * Clear the selected plan from the component state.
         */
        selectAnotherPlan: function() {
            this.selectedPlan = null;
            this.registerForm.plan = '';
        },


        /*
         * Initialize the registration process.
         */
        register: function(e) {
            self = this;

            e.preventDefault();

            this.cardForm.errors = [];
            this.registerForm.errors = [];
            this.registerForm.registering = true;

            if (this.freePlanIsSelected) {
                return this.sendRegistration();
            }

            /*
                Here we will build the payload to send to Stripe, which will
                return a token. This token can be used to make charges on
                the user's credit cards instead of storing the numbers.
            */
            payload = {
                name: this.registerForm.name,
                number: this.cardForm.number,
                cvc: this.cardForm.cvc,
                exp_month: this.cardForm.month,
                exp_year: this.cardForm.year,
                address_zip: this.cardForm.zip
            };

            Stripe.card.createToken(payload, function(status, response) {
                if (response.error) {
                    self.cardForm.errors.push(response.error.message);
                    self.registerForm.registering = false;
                } else {
                    self.registerForm.stripe_token = response.id;
                    self.sendRegistration();
                }
            });
        },


        /*
         * After obtaining the Stripe token, send the registration to Spark.
         */
        sendRegistration: function() {
            if (this.currentCoupon && ! this.freePlanIsSelected) {
                this.registerForm.coupon = this.currentCoupon.id;
            }

            this.$http.post('/register', this.registerForm)
                .success(function(response) {
                    window.location = '/home';
                })
                .error(function(errors) {
                    this.registerForm.registering = false;
                    setErrorsOnForm(this.registerForm, errors);
                });
        },


        /*
         * Get the proper column width based on the number of plans.
         */
        getPlanColumnWidth: function(count) {
            switch (count) {
                case 2:
                    return 6;
                case 3:
                    return 4;
                case 4:
                    return 3;
                default:
                    console.error("Spark only supports up to 4 plans per interval.");
            }
        },
    }
});

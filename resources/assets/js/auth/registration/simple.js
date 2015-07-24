var registerApp = new Vue({
    el: '#spark-register-screen',

    data: {
        registerForm: {
            name: '', email: '', password: '', password_confirmation: '',
            plan: '', terms: false, errors: [], registering: false
        }
    },

    methods: {
        register: function(e) {
            self = this;

            e.preventDefault();

            this.registerForm.errors = [];
            this.registerForm.registering = true;

            this.$http.post('/register', this.registerForm)
                .success(function(response) {
                    window.location = '/home';
                })
                .error(function(errors) {
                    this.registerForm.registering = false;
                    setErrorsOnForm(this.registerForm, errors);
                });
        }
    }
});

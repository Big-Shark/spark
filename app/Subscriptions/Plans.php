<?php

namespace Laravel\Spark\Subscriptions;

use Countable;
use Exception;
use JsonSerializable;

class Plans implements Countable, JsonSerializable
{
    /**
     * All of the defined plans.
     *
     * @var array
     */
    protected $plans = [];

    /**
     * Create a new plan instance.
     *
     * @param  string  $name
     * @param  string  $id
     * @return \Laravel\Spark\Subscriptions\Plan
     */
    public function create($name, $id)
    {
        return $this->add(new Plan($name, $id));
    }

    /**
     * Get plan matching a given ID.
     *
     * @param  string  $id
     * @return \Laravel\Spark\Subscriptions\Plan
     */
    public function find($id)
    {
        foreach ($this->plans as $plan) {
            if ($plan->id === $id) {
                return $plan;
            }
        }

        throw new Exception("Unable to find plan with ID [{$id}].");
    }

    /**
     * Add a plan to the plan collection.
     *
     * @param  \Laravel\Spark\Subscriptions\Plan  $plan
     * @return \Laravel\Spark\Subscriptions\Plan
     */
    public function add(Plan $plan)
    {
        $this->plans[] = $plan;

        return $plan;
    }

    /**
     * Determine if the plan collection has paid plans.
     *
     * @return bool
     */
    public function hasPaidPlans()
    {
        return count($this->paid()) > 0;
    }

    /**
     * Get all of the plans that require payment (price > 0).
     *
     * @return array
     */
    public function paid()
    {
        return array_values(array_filter($this->plans, function ($plan) {
            return $plan->price > 0;
        }));
    }

    /**
     * Get all of the monthly plans for a given tier.
     *
     * @return array
     */
    public function tier($tier)
    {
        return array_values(array_filter($this->plans, function ($plan) use ($tier) {
            return $plan->tier === $tier;
        }));
    }

    /**
     * Get all of the monthly plans for the application.
     *
     * @return array
     */
    public function monthly()
    {
        return array_values(array_filter($this->plans, function ($plan) {
            return $plan->isMonthly();
        }));
    }

    /**
     * Get all of the yearly plans for the application.
     *
     * @return array
     */
    public function yearly()
    {
        return array_values(array_filter($this->plans, function ($plan) {
            return $plan->isYearly();
        }));
    }

    /**
     * Get all of the plans that are active.
     *
     * @return array
     */
    public function active()
    {
        return array_values(array_filter($this->plans, function ($plan) {
            return $plan->isActive();
        }));
    }

    /**
     * Determine the number of plans in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->plans);
    }

    /**
     * Get the JSON serializable fields for the object.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->plans;
    }
}

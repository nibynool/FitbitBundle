<?php
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class FoodGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class FoodGateway extends EndpointGateway {

    /**
     * Get user foods for specific date
     *
     * @access public
     *
     * @todo Add validation for the date
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFoods($date)
    {
        $dateStr = $date->format('Y-m-d');

        try
        {
	        $returnValue = $this->makeApiRequest('user/' . $this->userID . '/foods/log/date/' . $dateStr);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Get user recent foods
     *
     * @access public
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getRecentFoods()
    {
	    try
	    {
	        $returnValue = $this->makeApiRequest('user/-/foods/log/recent');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException($e->getMessage());
	    }
	    return $returnValue;
    }

    /**
     * Get user frequent foods
     *
     * @access public
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFrequentFoods()
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/foods/log/frequent');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Get user favorite foods
     *
     * @access public
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFavoriteFoods()
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/foods/log/favorite');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Log user food
     *
     * @access public
     *
     * @todo Add validation for the date
     *
     * @param \DateTime $date Food log date
     * @param string $foodId Food Id from foods database (see searchFoods)
     * @param string $mealTypeId Meal Type Id from foods database (see searchFoods)
     * @param string $unitId Unit Id, should be allowed for this food (see getFoodUnits and searchFoods)
     * @param string $amount Amount in specified units
     * @param string $foodName
     * @param int $calories
     * @param string $brandName
     * @param array $nutrition
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logFood(\DateTime $date, $foodId, $mealTypeId, $unitId, $amount, $foodName = null, $calories = null, $brandName = null, $nutrition = null)
    {
        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        if (isset($foodName))
        {
            $parameters['foodName'] = $foodName;
            $parameters['calories'] = $calories;
            if (isset($brandName)) $parameters['brandName'] = $brandName;
            if (isset($nutrition))
            {
                foreach ($nutrition as $i => $value)
                {
                    $parameters[$i] = $nutrition[$i];
                }
            }
        }
        else $parameters['foodId'] = $foodId;
        $parameters['mealTypeId'] = $mealTypeId;
        $parameters['unitId'] = $unitId;
        $parameters['amount'] = $amount;

        try
        {
	        $returnValue = $this->makeApiRequest('user/-/foods/log', 'POST');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Delete user food
     *
     * @access public
     *
     * @param string $id Food log id
     * @throws FBException
     * @return bool
     */
    public function deleteFood($id)
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/foods/log/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Add user favorite food
     *
     * @access public
     *
     * @param string $id Food log id
     * @throws FBException
     * @return bool
     */
    public function addFavoriteFood($id)
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/foods/log/favorite/' . $id, 'POST');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Delete user favorite food
     *
     * @access public
     *
     * @param string $id Food log id
     * @throws FBException
     * @return bool
     */
    public function deleteFavoriteFood($id)
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/foods/log/favorite/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Get user meal sets
     *
     * @access public
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getMeals()
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/meals');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Get food units library
     *
     * @access public
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFoodUnits()
    {
        try
        {
	        $returnValue = $this->makeApiRequest('foods/units');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Search for foods in foods database
     *
     * @access public
     *
     * @todo Add validation for the query
     * @todo Can we create a query builder?  Do we even need one?
     *
     * @param string $query Search query
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function searchFoods($query)
    {
        try
        {
	        $returnValue = $this->makeApiRequest('foods/search', 'GET', array('query' => $query));
        }
        catch(\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Get description of specific food from food db (or private for the user)
     *
     * @access public
     *
     * @param  string $id Food Id
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFood($id)
    {
        try
        {
	        $returnValue = $this->makeApiRequest('foods/' . $id);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Create private foods for a user
     *
     * @access public
     *
     * @param string $name Food name
     * @param string $defaultFoodMeasurementUnitId Unit id of the default measurement unit
     * @param string $defaultServingSize Default serving size in measurement units
     * @param string $calories Calories in default serving
     * @param string $description
     * @param string $formType ("LIQUID" or "DRY)
     * @param string $nutrition Array of nutritional values, see http://wiki.fitbit.com/display/API/API-Create-Food
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function createFood($name, $defaultFoodMeasurementUnitId, $defaultServingSize, $calories, $description = null, $formType = null, $nutrition = null)
    {
        $parameters = array();
        $parameters['name'] = $name;
        $parameters['defaultFoodMeasurementUnitId'] = $defaultFoodMeasurementUnitId;
        $parameters['defaultServingSize'] = $defaultServingSize;
        $parameters['calories'] = $calories;
        if (isset($description)) $parameters['description'] = $description;
        if (isset($formType)) $parameters['formType'] = $formType;
        if (isset($nutrition))
        {
            foreach ($nutrition as $i => $value)
            {
                $parameters[$i] = $nutrition[$i];
            }
        }

        try
        {
	        $returnValue = $this->makeApiRequest('foods', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }
}

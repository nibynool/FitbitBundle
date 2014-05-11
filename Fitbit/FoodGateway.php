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
     * @version 0.5.0
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
	        return $this->makeApiRequest('user/' . $this->userID . '/foods/log/date/' . $dateStr);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Get user recent foods
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getRecentFoods()
    {
	    try
	    {
	        return $this->makeApiRequest('user/-/foods/log/recent');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException($e->getMessage());
	    }
    }

    /**
     * Get user frequent foods
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFrequentFoods()
    {
        try
        {
	        return $this->makeApiRequest('user/-/foods/log/frequent');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Get user favorite foods
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFavoriteFoods()
    {
        try
        {
	        return $this->makeApiRequest('user/-/foods/log/favorite');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
   }

    /**
     * Log user food
     *
     * @access public
     * @version 0.5.0
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
	        return $this->makeApiRequest('user/-/foods/log', 'POST');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Delete user food
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Food log id
     * @throws FBException
     * @return bool
     */
    public function deleteFood($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/foods/log/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Add user favorite food
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Food log id
     * @throws FBException
     * @return bool
     */
    public function addFavoriteFood($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/foods/log/favorite/' . $id, 'POST');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Delete user favorite food
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Food log id
     * @throws FBException
     * @return bool
     */
    public function deleteFavoriteFood($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/foods/log/favorite/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Get user meal sets
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getMeals()
    {
        try
        {
	        return $this->makeApiRequest('user/-/meals');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Get food units library
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFoodUnits()
    {
        try
        {
	        return $this->makeApiRequest('foods/units');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Search for foods in foods database
     *
     * @access public
     * @version 0.5.0
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
	        return $this->makeApiRequest('foods/search', 'GET', array('query' => $query));
        }
        catch(\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Get description of specific food from food db (or private for the user)
     *
     * @access public
     * @version 0.5.0
     *
     * @param  string $id Food Id
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFood($id)
    {
        try
        {
	        return $this->makeApiRequest('foods/' . $id);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }

    /**
     * Create private foods for a user
     *
     * @access public
     * @version 0.5.0
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
	        return $this->makeApiRequest('foods', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
    }
}

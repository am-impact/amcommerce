<?php
namespace Craft;

/**
 * AmCommerce - Order Element Type
 */
class AmCommerce_OrderElementType extends BaseElementType
{
    /**
     * Returns the element type name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Order');
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public function hasContent()
    {
        return false;
    }

    /**
     * Returns whether this element type has titles.
     *
     * @return bool
     */
    public function hasTitles()
    {
        return false;
    }

    /**
     * Returns whether this element type has statuses.
     *
     * @return bool
     */
    public function hasStatuses()
    {
        return true;
    }

    /**
     * Returns this element type's statuses.
     *
     * @return array
     */
    public function getStatuses()
    {
        return array(
            AmCommerceModel::StatusCancelled => Craft::t('Cancelled'),
            AmCommerceModel::StatusPending => Craft::t('Pending'),
            AmCommerceModel::StatusPaid => Craft::t('Paid'),
            AmCommerceModel::StatusFinished => Craft::t('Finished')
        );
    }

    /**
     * Returns this element type's sources.
     *
     * @param string|null $context
     *
     * @return array|false
     */
    public function getSources($context = null)
    {
        $sources = array();

        foreach ($this->getStatuses() as $keySource => $status)
        {
            $key = 'status:'.$keySource;
            $sources[$key] = array(
                'label'    => $status,
                'criteria' => array('status' => $status)
            );
        }
        return $sources;
    }

    /**
     * Return this element type's sortable attributes.
     *
     * @return array
     */
    public function defineSortableAttributes()
    {
        $attributes = array();
        return $attributes;
    }

    /**
     * Returns the attributes that can be shown/sorted by in table views.
     *
     * @param string|null $source
     *
     * @return array
     */
    public function defineTableAttributes($source = null)
    {
        return array(
            'title' => Craft::t('Title')
        );
    }

    /**
     * Modifies an element query targeting elements of this type.
     *
     * @param DbCommand            $query
     * @param ElementCriteriaModel $criteria
     *
     * @return mixed
     */
    public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
    {
        $query
        ->addSelect('orders.*')
        ->join(AmCommerce_OrderRecord::getTableName() . ' orders', 'orders.id = elements.id');
    }

    /**
     * Populates an element model based on a query result.
     *
     * @param array $row
     *
     * @return AmCommerce_OrderModel
     */
    public function populateElementModel($row)
    {
        return AmCommerce_OrderModel::populateModel($row);
    }

    /**
     * @inheritDoc IElementType::saveElement()
     *
     * @param BaseElementModel $element
     * @param array            $params
     *
     * @return bool
     */
    public function saveElement(BaseElementModel $element, $params)
    {
        return craft()->amCommerce_orders->saveOrder($element);
    }
}
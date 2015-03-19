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
        $sources = array(
            '*' => array(
                'label' => Craft::t('All Statuses'),
            )
        );

        foreach ($this->getStatuses() as $keySource => $status)
        {
            $key = 'status:'.$keySource;
            $sources[$key] = array(
                'label'    => $status,
                'criteria' => array('status' => $keySource)
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
        $attributes = array(
            'orderNumber' => Craft::t('Order Number')
        );
        return $attributes;
    }

    /**
     * Return searchable attributes.
     *
     * @return array
     */
    public function defineSearchableAttributes()
    {
        return array('orderNumber');
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
            'orderNumber' => Craft::t('Order Number'),
            'dateCreated' => Craft::t('Create Date'),
            'totalPrice' => Craft::t('Total Price')
        );
    }

    /**
     * @inheritDoc IElementType::getTableAttributeHtml()
     *
     * @param BaseElementModel $element
     * @param string           $attribute
     *
     * @return string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        switch ($attribute)
        {
            case 'dateCreated':
            {
                return $element->dateCreated->localeDate();
            }

            default:
            {
                return parent::getTableAttributeHtml($element, $attribute);
            }
        }
    }

    /**
     * Defines any custom element criteria attributes for this element type.
     *
     * @return array
     */
    public function defineCriteriaAttributes()
    {
        return array(
            'status' => AttributeType::String
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

        if (!empty($criteria->status)) {
            $query->andWhere(DbHelper::parseParam('orders.status', $criteria->status, $query->params));
        }
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
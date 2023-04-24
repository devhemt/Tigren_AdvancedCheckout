<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class TimePicker extends AbstractFieldArray
{
    protected function _prepareToRender()
    {
        $this->addColumn('time_start', ['label' => __('From'), 'class' => 'timepicker']);
        $this->addColumn('time_end', ['label' => __('To'), 'class' => 'timepicker']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Time');
        parent::_prepareToRender();
    }

    /**
     * Prepare existing row data object
     * Convert backend time format "10:00:00" to front format "10:00 AM"
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $key = 'time';
        if (!isset($row[$key])) {
            return;
        }
        $rowId = $row['_id'];
    }

    /**
     * Get the grid and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = parent::_getElementHtml($element);
        $script = /** @lang text */
            <<< JS
            <script type="text/javascript">
                require(["jquery", "jquery/ui","mage/calendar"], function (jq) {
                    jq(function(){
                        function bindTimePicker() {
                            setTimeout(function() {
                                jq(".timepicker").timepicker({
                                    timeFormat: 'hh:mm tt',
                                });
                            }, 50);
                        }
                        bindTimePicker();
                        jq("button.action-add").on("click", function(e) {
                            bindTimePicker();
                        });
                    });
                });
            </script>
        JS;
        $html .= $script;
        return $html;
    }
}

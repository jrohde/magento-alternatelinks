<?php

class Rohde_Seo_Model_System_Config_Source_Dropdown_Values
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'language-region',
                'label' => 'Language and Region',
            ),
            array(
                'value' => 'language',
                'label' => 'Language Only',
            ),
        );
    }
}

?>
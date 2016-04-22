<?php

class Rohde_BetterSeo_Model_System_Config_Source_Dropdown_Formats
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
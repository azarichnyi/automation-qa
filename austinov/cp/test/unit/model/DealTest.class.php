<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class DealCategoryTest extends DealCategory
{
    
    public function getUrl()
    {
        return 'cp_img_web_bar.png';
    }
    
}

/**
 * Description of BusinessTest
 *
 * @author rodush
 */
class DealTest extends Deal
{
    public function getImage()
    {
        return 'image.jpg';
    }
    
    public function getDealCategory()
    {
        return new DealCategoryTest;
    }
}

class DealTestNoImage extends DealTest
{
    public function getImage()
    {
        return NULL;
    }
}


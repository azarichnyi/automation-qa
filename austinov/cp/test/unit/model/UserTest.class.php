<?php

class UserTest extends User
{
    
    public function getPainBucksDetails() 
    { 
        return $this->painBucksDetails;
    }
    
    public function addPainBucksDd($painBucks)
    {
        return parent::addPainBucksDd($painBucks);
    }    

    public function addPainBucksDc($painBucks)
    {
        return parent::addPainBucksDc($painBucks);
    }    
    
    public function addPainBucksCc($painBucks)
    {
        return parent::addPainBucksCc($painBucks);
    }        
    
    public function addPainBucksContrib($painBucks)
    {
        return parent::addPainBucksContrib($painBucks);
    }    
    

}
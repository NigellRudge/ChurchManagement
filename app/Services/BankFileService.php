<?php


namespace App\Services;


use App\Models\BankFile;

class BankFileService
{
    private $seedService;
    private $offeringService;

    public function __construct(OfferingService $offeringService, SeedsService $seedService)
    {
        $this->seedService = $seedService;
        $this->offeringService = $offeringService;
    }

    public function getAllFiles(array $filterOptions){
        $items = BankFile::all();

        return $items;
    }

    public function addBankFile(array $data){
        try {
            return true;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function removeBankFile($bankFileId){

    }

    public function editBankFile(array $data,$bankFileId){

    }

    public function addTransaction(array $data,$bankFileId){

    }

    public function editTransaction(array $data,$bankFileId){

    }

    public function destroyTransaction(array $data,$bankFileId){

    }
}

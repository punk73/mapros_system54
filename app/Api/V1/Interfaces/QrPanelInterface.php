<?php
namespace App\Api\V1\Interfaces;

interface QrPanelInterface {
    public function storeQrPanel($data, $guid = null);
    public function getGuidMaster();
    public function getGuidTicket();
    public function getParameter();
}
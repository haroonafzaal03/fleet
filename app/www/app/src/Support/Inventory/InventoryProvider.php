<?php

namespace App\Support\Inventory;


use App\Model\DeliveryOrderItems;
use App\Model\PurchaseOrderItems;
use App\Model\WarehouseStock;
use App\Model\WarehouseStockLogs;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

class InventoryProvider
{
    use Injectable;
    use Configurable;
    use Extensible;

    public function increaseInventory(PurchaseOrderItems $purchaseOrderItem): void
    {
        $warehouseLog = WarehouseStockLogs::get()->filter([
            'InventoryItemID' => $purchaseOrderItem->InventoryItem()->ID,
            'WarehouseID' => $purchaseOrderItem->PurchaseOrder()->Warehouse()->ID,
            'PurchaseOrderItemID' => $purchaseOrderItem->ID
        ]);

        if ($warehouseLog->exists() && $warehouseLog = $warehouseLog->last()) {
            $warehouseLog->QTY = $purchaseOrderItem->QTY;
        } else {
            $warehouseLog = WarehouseStockLogs::create([
                'QTY' => $purchaseOrderItem->QTY,
                'InventoryItemID' => $purchaseOrderItem->InventoryItem()->ID,
                'WarehouseID' => $purchaseOrderItem->PurchaseOrder()->Warehouse()->ID,
                'PurchaseOrderItemID' => $purchaseOrderItem->ID
            ]);
        }

        $warehouseLog->write();

        $this->__updateWarehouseInventory($purchaseOrderItem->InventoryItem()->ID, $purchaseOrderItem->PurchaseOrder()->Warehouse()->ID);
    }

    public function decreaseInventory(DeliveryOrderItems $deliveryOrderItems)
    {
        $warehouseLog = WarehouseStockLogs::create([
            'QTY' => -1 * $deliveryOrderItems->QTY,
            'InventoryItemID' => $deliveryOrderItems->InventoryItem()->ID,
            'WarehouseID' => $deliveryOrderItems->DeliveryOrder()->Warehouse()->ID,
            'DeliveryOrderItemID' => $deliveryOrderItems->ID
        ]);

        $warehouseLog->write();

        $this->__updateWarehouseInventory($deliveryOrderItems->InventoryItem()->ID, $deliveryOrderItems->DeliveryOrder()->Warehouse()->ID);
    }

    private function __updateWarehouseInventory($inventoryItemID, $warehouseID)
    {
        $currentInventory = WarehouseStockLogs::get()->filter([
            'InventoryItemID' => $inventoryItemID,
            'WarehouseID' => $warehouseID
        ])->sum("QTY");

        $warehouseStock = WarehouseStock::get()->filter([
            'InventoryItemID' => $inventoryItemID,
            'WarehouseID' => $warehouseID
        ]);

        if ($warehouseStock->exists() && $warehouseStock = $warehouseStock->last()) {
            $warehouseStock->CalculatedStock = $currentInventory;
        } else {
            $warehouseStock = WarehouseStock::create([
                'InventoryItemID' => $inventoryItemID,
                'WarehouseID' => $warehouseID,
                'CalculatedStock' => $currentInventory
            ]);
        }
        $warehouseStock->write();
    }
}

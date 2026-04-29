<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Controller;

use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Service\PurchaseFlow\PurchaseFlowResult;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Service\Attribute\Required;

class AbstractShoppingController extends AbstractController
{
    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;
   
    

    /**
     * @param ItemHolderInterface $itemHolder
     * @param bool $returnResponse レスポンスを返すかどうか. falseの場合はPurchaseFlowResultを返す.
     *
     * @return PurchaseFlowResult|RedirectResponse|null
     */
    protected function executePurchaseFlow(ItemHolderInterface $itemHolder, $returnResponse = true)
    {
        /** @var PurchaseFlowResult $flowResult */
        $flowResult = $this->purchaseFlow->validate($itemHolder, new PurchaseContext(clone $itemHolder, $itemHolder->getCustomer()));
        foreach ($flowResult->getWarning() as $warning) {
            $this->addWarning($warning->getMessage());
        }
        foreach ($flowResult->getErrors() as $error) {
            $this->addError($error->getMessage());
        }



        return null;
    }
}

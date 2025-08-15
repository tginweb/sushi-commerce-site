import {BasketItem} from "@/gql/gen";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export function fillBasketItemByProduct(item: BasketItem, product: ProductElementModel) {
    const priceBase = product.PRICE.PRICE
    item.PRICE_BASE = priceBase || 0
    item.PRICE = priceBase || 0
    item.NAME = product.NAME
    item.ELEMENT = product
}

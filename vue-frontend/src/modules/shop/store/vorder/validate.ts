import {ValidateRules} from "@/core/types";
import {OrderAttributesValue} from "@/gql/gen";

export const getRulesPickup = (attrValue: Partial<OrderAttributesValue>) => {
    const res: ValidateRules = []
    res.push(v => !!attrValue.PICKUP_DEPARTMENT || 'Не выбрано подразделение самовывоза')
    return res
}

export const getRulesDelivery = (attrValue: Partial<OrderAttributesValue>) => {
    const res: ValidateRules = []
    res.push(v => !!attrValue.ADDRESS || 'Не указан адрес доставки')
    res.push(v => !!attrValue.HOUSE || 'Не указан дом')
    return res
}

export const getRulesTime = (attrValue: Partial<OrderAttributesValue>) => {
    const res: ValidateRules = [
        v => !!attrValue['DATE'] || !!attrValue['TIME'] || 'не выбрана дата и время',
        v => !!attrValue['DATE'] || 'не выбрана дата',
        v => !!attrValue['TIME'] || 'не выбрано время',
        v => {
            return true
        }
    ]
    return res
}


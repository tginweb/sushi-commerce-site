import {Nullable, ValidatorPhone} from "@/core/types";

export default function checkPhone(val: Nullable<string>, params: Omit<ValidatorPhone, 'type'> = {}, returnMessage = false) {
    const _valNumeric = (val || '').replace(/[^\d]/g, '')
    return _valNumeric.match(/^((\+7|7|8)[0-9]{10}|[^78][0-9]{9})$/) ? true : (returnMessage ? 'неверный формат' : false)
}

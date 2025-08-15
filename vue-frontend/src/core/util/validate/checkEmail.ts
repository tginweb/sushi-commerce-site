import {Nullable, ValidatorEmail} from "@/core/types";

export default function checkEmail(val: Nullable<string>, params: Omit<ValidatorEmail, 'type'> = {}, returnMessage = false) {
    return (val || '').match(/\S+@\S+\.\S+/) ? true : (returnMessage ? 'неверный формат email' : false)
}

import checkEmail from "./checkEmail";
import checkPhone from "./checkPhone";

export default function checkPhoneOrEmail(val: any, returnMessage = false) {
  const checkPhoneResult = checkPhone(val, {})
  const checkEmailResult = checkEmail(val, {})

  if (checkPhoneResult === true || checkEmailResult === true) {
    return true
  } else {
    return returnMessage ? 'неверный формат телефона или email' : false
  }
}

import { QForm } from "quasar";
import scrollToQuasarErrors from "./scrollToQuasarErrors";

export default async function quasarFormValidate(
  form?: QForm | null,
  scrollContainer?: HTMLElement
) {
  if (!form) return false;
  if (await form.validate(true)) return true;
  scrollToQuasarErrors(form.$el, scrollContainer);
}


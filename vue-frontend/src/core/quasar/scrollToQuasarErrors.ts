import { scrollTo } from "../util/scrollTo";

export default function scrollToQuasarErrors(
  contaner?: HTMLElement,
  scrollContainer?: Element | null
) {
  let errorElm: Element | null = null;

  if (contaner) {
    errorElm = contaner.querySelector(".q-field--error");
    if (!scrollContainer) scrollContainer = contaner.closest(".scroll");
  } else {
    errorElm = document.querySelector(".q-field--error");
    if (errorElm && !scrollContainer)
      scrollContainer = errorElm.closest(".scroll");
  }

  if (errorElm) {
    scrollTo(errorElm as HTMLElement, 20, {}, scrollContainer as HTMLElement);
  }
}

//@ts-ignore
import { defineBoot } from "#q-app/wrappers";
import { App } from "vue";
import { Carousel, Navigation, Pagination, Slide } from "vue3-carousel";
import "vue3-carousel/carousel.css";
//@ts-ignore
import JsonViewer from "vue3-json-viewer";
//import "vue3-json-viewer/dist/index.css";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import { intersectDirective } from "@/core/directive/intersect";
import { stickyStateDirective } from "@/core/directive/sticky-state";
import { tabsScrollableDirective } from "@/core/directive/tabs-scrollable";
import selectTexareaDirective  from "@/core/directive/select-textarea";

import StackItemModal from "@/packages/stack-router/components/StackItemModal.vue";

import BottomSheet from "@/components/BottomSheet/BottomSheet.vue";

import Accordion from "@/components/Accordion.vue";
import AnimatedNumber from "@/components/AnimatedNumber.vue";
import ButtonsToggle from "@/components/ButtonsToggle.vue";
import ChunkPageDelivery from "@/components/Chunks/PageDelivery/PageDelivery.vue";
import DataFields from "@/components/DataFields.vue";
import DialogBody from "@/components/Dialog/Body.vue";
import Dialog from "@/components/Dialog/Dialog.vue";
import DialogFooter from "@/components/Dialog/Footer.vue";
import DialogHeader from "@/components/Dialog/Header.vue";
import OptionsCards from "@/components/OptionsCards.vue";
import OptionsInline from "@/components/OptionsInline.vue";
import QuantityInput from "@/components/QuantityInput.vue";
import RuntimeContent from "@/components/RuntimeContent.vue";

import { createYmaps } from "vue-yandex-maps";
import { bus } from "./bus";

import VueScrollPicker from "vue-scroll-picker";
import "vue-scroll-picker/style.css";

export default defineBoot((ctx: any) => {
  const app: App = ctx.app;

  app.provide("bus", bus);

  app.use(JsonViewer);

  app.use(Toast, {
    position: "bottom-center",
  });

  app.use(
    createYmaps({
      apikey: "dee49fd2-76a4-457a-9f25-856c945f3ed4",
    })
  );

  app.directive("intersect", intersectDirective);
  app.directive("sticky-state", stickyStateDirective);
  app.directive("tabs-scrollable", tabsScrollableDirective);
  app.directive("select-textarea", selectTexareaDirective);

  app.component("Carousel", Carousel);
  app.component("CarouselSlide", Slide);
  app.component("CarouselPagination", Pagination);
  app.component("CarouselNavigation", Navigation);

  app.component("StackItemModal", StackItemModal);

  app.component("BottomSheet", BottomSheet);
  app.component("Dialog", Dialog);
  app.component("DialogHeader", DialogHeader);
  app.component("DialogFooter", DialogFooter);
  app.component("DialogBody", DialogBody);
  app.component("ButtonsToggle", ButtonsToggle);
  app.component("OptionsCards", OptionsCards);
  app.component("OptionsInline", OptionsInline);
  app.component("RuntimeContent", RuntimeContent);
  app.component("ChunkPageDelivery", ChunkPageDelivery);
  app.component("QuantityInput", QuantityInput);
  app.component("AnimatedNumber", AnimatedNumber);
  app.component("DataFields", DataFields);

  app.component("Accordion", Accordion);

  app.use(VueScrollPicker);
});

if (process.env.PROD && "serviceWorker" in navigator) {
  window.addEventListener("load", () => {
    navigator.serviceWorker.register("/service-worker.js");
  });
}

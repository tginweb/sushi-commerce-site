import { shallowReactive } from "vue";
import { RequestContext } from "./createRequestContext";

export const createRequestContextManager = () => {
  const contexts = shallowReactive<Record<string, RequestContext>>({});

  const registerContext = (name: string, context: RequestContext) => {
    contexts[name] = context;
  };

  const setGlobalContext = (context: RequestContext) => {
    contexts.global = context;
  };

  const getGlobalContext = () => {
    return contexts.global as RequestContext;
  };

  return {
    contexts,
    registerContext,
    setGlobalContext,
    getGlobalContext,
  };
};

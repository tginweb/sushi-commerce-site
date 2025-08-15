import { ValidateRule, Validator } from "@/core/types";

import validate from "./validate";

export default function createRules(...validators: (ValidateRule | string)[]): any {
  return validators
    .filter((validator) => !!validator)
    .map((validator) => {
      if (typeof validator === "function") {
        return validator;
      } else if (typeof validator === "string") {
        return (v: any) => {
          return validate(v, { type: validator as Validator["type"] }, true);
        };
      } else {
        return (v: any) => {
          return validate(v, validator as unknown as Validator, true);
        };
      }
    });
}

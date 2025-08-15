import { ErrorInterfaceGenqlSelection } from "@/gql/gen";

export const ErrorFields: ErrorInterfaceGenqlSelection = {
  type: true,
  name: true,
  rel: true,
  fieldMessage: true,
  message: true,
  messages: true,
  on_RateError: {
    ttl: true,
  },
  on_OtpError: {
    param: true,
  },
  on_AccessError: {
    authRedirect: true,
  },
  on_FormError: {
    fieldLabel: true,
    fieldName: true,
  },
};

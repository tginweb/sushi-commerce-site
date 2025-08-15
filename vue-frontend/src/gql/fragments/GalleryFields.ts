import { ElementGenqlSelection } from "@/gql/gen";

// Using base Element selection until backend types are generated
export const GalleryFields: ElementGenqlSelection = {
  ID: true,
  NAME: true,
  CODE: true,
  DETAIL_IMAGE: {
    ID: true,
    SRC: true,
    SRC_DEFAULT: true,
    ORIGINAL_NAME: true,
  },
  LIST_IMAGE: {
    ID: true,
    SRC: true,
    SRC_DEFAULT: true,
    ORIGINAL_NAME: true,
  },
  PREVIEW_TEXT: true,
  DETAIL_TEXT: true,
  // Include properties for images if present
  PROPERTIES: {
    IMAGES: {
      ID: true,
      SRC: true,
      SRC_DEFAULT: true,
      ORIGINAL_NAME: true,
    },
    DESCRIPTION: true,
    DATE: true,
  } as any,
} as any;

import {WebcamGenqlSelection} from "@/gql/gen";

export const CameraElementFields: WebcamGenqlSelection = {
    ID: true,
    NAME: true,
    PROPERTIES: {
        CODE: true,
    }
}

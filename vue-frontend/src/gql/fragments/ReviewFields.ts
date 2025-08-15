import {ReviewElementGenqlSelection} from "@/gql/gen";

export const ReviewFields: ReviewElementGenqlSelection = {
    "ID": true,
    "NAME": true,
    "DETAIL_TEXT": true,
    "ACTIVE_FROM": true,
    "PROPERTIES": {
        "AUTHOR_NAME": true,
        "ANSWER": true,
        "RATING": true,
    },
}

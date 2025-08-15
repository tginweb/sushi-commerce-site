import {ID} from "@/core/types";
import toInt from "@/core/util/toInt";

export function toIDS(ids: ID[]) {
    return ids.map(id => toInt(id))
}


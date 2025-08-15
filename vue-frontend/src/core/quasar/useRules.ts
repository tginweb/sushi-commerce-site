import createRules from "@/core/util/validate/createRules";
import {computed} from "vue";

export default function useRules(rules) {
    const rulesComputed = computed(() => createRules(rules))
    return rulesComputed
}

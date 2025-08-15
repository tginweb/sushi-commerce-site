import {GenerateCombinedType} from "@/core/types";
import {createTabbedViewContextKey} from "@/core/hooks/useTabbedViews";

export type DeliverySelectViewModeMap = {
    profile: 'list' | 'edit'
    pickup: 'list'
}

export type DeliverySelectViewName = GenerateCombinedType<DeliverySelectViewModeMap, '.'>;

export const DeliverySelectContextKey = createTabbedViewContextKey<DeliverySelectViewModeMap>("DeliverySelectContext");

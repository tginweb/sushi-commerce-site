import {Product} from "@/gql/gen";
import parseMinutes from "@/core/util/date/parseMinutes";
import formatSchedule, {ScheduleItem} from "@/core/util/formatSchedule";
import {
    ProductBenefitModel,
    ProductSchedule,
    ProductScheduleItem,
    ProductSetItemModel
} from "@/modules/shop/store/catalog";
import {useShopStateStore} from "@/modules/shop/store/state";
import {CatalogStoreGettersLoader} from "@/modules/shop/store/util/catalog";
import {ValidateRuleResult} from "@/core/types";

export type ProductElementModel = Product & {
    _isModel: true,
    isSaleAllow: () => boolean
    getBenefits: () => ProductBenefitModel[]
    getSetItems: () => ProductSetItemModel[];
    getSchedule: () => ProductSchedule | null
    checkSchedule: (targetDate: Date) => ValidateRuleResult
    getPriceSource: () => number
}

export default function useProduct(product: Product, gettersLoader: CatalogStoreGettersLoader): ProductElementModel {

    const state = useShopStateStore()

    const isSaleAllow = () => {
        const price = product.PRICE?.PRICE || 0;
        return !!product.ACTIVE && (price > 0) && (product.IBLOCK_SECTION_ID !== 94);
    }

    const getSchedule = () => {
        const result: ProductSchedule = {
            items: [],
            weekdays: {},
            titles: [],
        };

        const items = (product.PROPERTIES.AVAILABLE_SCHEDULE || [])
            .filter((item) => !!item)
            .map((item) => {
                const resultItem = {
                    dayCode: item?.DAY?.CODE || "",
                    dayLabel: item?.DAY?.VALUE || "",
                    timeFrom: item?.FROM || "",
                    timeTo: item?.TO || "",
                } as ProductScheduleItem;
                resultItem.timeHours =
                    "c " + resultItem.timeFrom + " до " + resultItem.timeTo;
                resultItem.dayWeekIndex =
                    ["weekday", "weekend"].indexOf(resultItem.dayCode) === -1
                        ? parseInt(resultItem.dayCode)
                        : 0;
                return resultItem;
            });

        if (items.length) {
            result.items = items;

            const itemByDayCode = items.reduce<Record<string, ProductScheduleItem>>(
                (map, item) => {
                    map[item.dayCode] = item;
                    return map;
                },
                {}
            );

            for (let dayWeekIndex = 1; dayWeekIndex <= 7; dayWeekIndex++) {
                let item: ProductScheduleItem | undefined = undefined;
                if (dayWeekIndex >= 6 && itemByDayCode.weekend) {
                    item = itemByDayCode.weekend;
                } else if (dayWeekIndex <= 5 && itemByDayCode.weekday) {
                    item = itemByDayCode.weekday;
                } else {
                    item = itemByDayCode[dayWeekIndex];
                }
                if (item) {
                    result.weekdays[dayWeekIndex] = {
                        startMinutes: parseMinutes(item.timeFrom),
                        endMinutes: parseMinutes(item.timeTo),
                        from: item.timeFrom,
                        to: item.timeTo,
                        hours: item.timeHours,
                    };
                }
            }

            const titles = formatSchedule(
                items
                    .filter((item) => item.dayWeekIndex > 0)
                    .map<ScheduleItem>((item) => {
                        return {
                            day: item.dayWeekIndex,
                            hours: item.timeHours,
                        };
                    })
            );

            if (itemByDayCode.weekday) {
                titles.push("будние " + itemByDayCode.weekday.timeHours);
            }

            if (itemByDayCode.weekend) {
                titles.push("выходные " + itemByDayCode.weekend.timeHours);
            }

            result.titles = titles;

            return result;
        }

        return null;
    };

    const checkSchedule = (targetDate: Date) => {
        const schedule = getSchedule();

        if (schedule) {
            const currentDay = targetDate.getDay() === 0 ? 7 : targetDate.getDay();
            const currentMinutes =
                targetDate.getHours() * 60 + targetDate.getMinutes();

            const daySchedule = schedule.weekdays[currentDay];

            if (!daySchedule) return false;

            const {startMinutes, endMinutes} = daySchedule;

            let res: boolean;

            if (startMinutes <= endMinutes) {
                // Обычный диапазон в пределах одних суток
                res = currentMinutes >= startMinutes && currentMinutes <= endMinutes;
            } else {
                // Диапазон переходит через полночь
                res = currentMinutes >= startMinutes || currentMinutes <= endMinutes;
            }

            if (!res) {
                return "доступно по времени только " + daySchedule.hours;
            }
        }

        return true;
    };

    const getBenefits = (withModels = false) => {
        const getters = gettersLoader()
        return product.BENEFITS.map((item) => ({
            ...item,
            product: getters.resolveProduct(item.PRODUCT || item.PRODUCT_ID) || null,
            productModel: withModels ? getters.resolveProductModel(item.PRODUCT || item.PRODUCT_ID) : null
        }));
    };

    const getSetItems = (withModels = false) => {
        const getters = gettersLoader()
        return product.SET_ITEMS.map((item) => ({
            ...item,
            product: getters.resolveProduct(item.PRODUCT || item.PRODUCT_ID) || null,
            productModel: withModels ? getters.resolveProductModel(item.PRODUCT || item.PRODUCT_ID) : null
        }));
    };

    const getPriceSource = () => {
        return product.PRICE?.PRICE || 0
    };

    return {
        ...product,
        _isModel: true,
        isSaleAllow,
        checkSchedule,
        getSchedule,
        getBenefits,
        getSetItems,
        getPriceSource
    };
};

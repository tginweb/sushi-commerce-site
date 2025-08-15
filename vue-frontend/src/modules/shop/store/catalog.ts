import {Product, ProductBenefit, ProductSection, ProductSetItem,} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {ID, Maybe} from "@/core/types";
import {treeBuild} from "@/core/util";
import {defineStore} from "pinia";
import {computed, nextTick, ref} from "vue";
import useProduct, {ProductElementModel} from "@/modules/shop/composable/useProduct";
import {useEntityDataloader} from "@/core/hooks/useEntityDataloader";
import {useGraphql} from "@/core/graphql/service";
import {toIDS} from "@/core/util/toIDS";
import {genqlBuild} from "@/core/graphql/genql/builder";
import {toID} from "@/core/util/toID";
import {CatalogStoreGettersLoader} from "@/modules/shop/store/util/catalog";
import {arrayIndex} from "@/core/util/arrayIndex";

const STORE_NAME = "shop-catalog";

export const useCatalogStore = defineStore(STORE_NAME, () => {

    const {registerScopeQuery} = useScopeQuery();
    const {query} = useGraphql()

    const loadGetters: CatalogStoreGettersLoader = () => {
        return {
            sections,
            products,
            productsById,
            productsModelsById,
            resolveProduct,
            resolveProductModel
        }
    }

    const products = ref<Product[]>([]);
    const sections = ref<ProductSection[]>([]);

    const productsById = computed(() => arrayIndex(products.value, 'ID'))
    const productsModels = computed(() => products.value.map(product => useProduct(product, loadGetters)))
    const productsModelsById = computed(() => arrayIndex(productsModels.value, (item) => item.ID))

    const sectionsById = computed(() => arrayIndex(sections.value, 'ID'))
    const sectionsByCode = computed(() => arrayIndex(sections.value, 'CODE'))
    const sectionsTree = computed(() => treeBuild(sections.value, "ID", "IBLOCK_SECTION_ID", "CHILDREN"))

    const productsBySection = computed(() => {
        return products.value.reduce<Record<string, Product[]>>((map, product) => {
            product.IBLOCK_SECTION_IDS.forEach((sectionId) => {
                if (!map[sectionId]) {
                    map[sectionId] = []
                }
                map[sectionId]!.push(product)
            })
            return map
        }, {})
    })

    const productsModelsBySection = computed(() => {
        return productsModels.value.reduce<Record<string, ProductElementModel[]>>((map, product) => {
            if (product.isSaleAllow()) {
                product.IBLOCK_SECTION_IDS.forEach((sectionId) => {
                    if (!map[sectionId]) {
                        map[sectionId] = []
                    }
                    map[sectionId]!.push(product)
                })
            }
            return map
        }, {})
    })

    const resolveProduct = (product: ID | Product) => {
        if (typeof product === 'number' || typeof product === 'string') {
            return productsById.value[product]
        } else if (typeof product === 'object') {
            if (productsById.value[product.ID]) {
                return productsById.value[product.ID]
            }
            return product
        }
    }

    const resolveProductModel = (product: ID | Product | ProductElementModel) => {
        if (typeof product === 'number' || typeof product === 'string') {
            return productsModelsById.value[product]
        } else if (typeof product === 'object') {
            if ('_isModel' in product) {
                return product
            } else {
                if (productsModelsById.value[product.ID]) {
                    return productsModelsById.value[product.ID]
                }
                return useProduct(product, loadGetters)
            }
        }
    }

    const fillProducts = (elements: Product[]) => {
        const newProducts = elements.filter(product => !productsById.value[product.ID])
        products.value = [...products.value, ...newProducts]
    }

    const fillProduct = (element: Product) => {
        fillProducts([element])
    }

    const fetchProducts = async (ids: ID[]): Promise<ProductElementModel[]> => {
        return (await query(genqlBuild({
            catalog_product_list: {
                __fragment: 'ProductElementFields',
                __args: {
                    filter: {ID: {in: toIDS(ids)}}
                }
            }
        }), {}) || [])
    }

    const fetchProduct = async (id: ID): Promise<ProductElementModel | null> => {
        return query(genqlBuild({
            catalog_product_single: {
                __fragment: 'ProductElementFields',
                __args: {
                    filter: {ID: {eq: toID(id)}}
                }
            }
        }), {})
    }

    const productsLoader = async (
        ids: (ID | Product | ProductElementModel)[],
    ): Promise<Maybe<ProductElementModel>[]> => {
        const missingIds = ids.filter((id) => {
            const element = resolveProductModel(id)
            return !element && (typeof id === 'number' || typeof id === 'string')
        }) as ID[]
        if (missingIds.length > 0) {
            const fetchedElements = await fetchProducts(missingIds)
            fillProducts(fetchedElements)
            await nextTick()
        }
        return ids.map((id) => resolveProductModel(id))
    }

    const ensureProduct = async (
        product: Maybe<ID | Product | ProductElementModel>
    ): Promise<Maybe<ProductElementModel>> => {
        if (!product) return null;
        let result: Maybe<ProductElementModel> = resolveProductModel(product);
        if (result) {
            return result;
        } else {
            if (typeof product === 'number' || typeof product === 'string') {
                result = await fetchProduct(product)
                if (result) {
                    fillProduct(result)
                    await nextTick()
                    return productsModelsById.value[result.ID]
                }
            }
        }
    }

    const ensureProducts = async (
        ids: (ID | Product | ProductElementModel)[],
    ) => {
        return (await productsLoader(ids)).filter(Boolean) as ProductElementModel[]
    }

    const ensureProductsById = async (
        ids: (ID | Product | ProductElementModel)[]
    ): Promise<Record<string, Maybe<ProductElementModel>>> => {
        return arrayIndex(await ensureProducts(ids), 'ID')
    }

    const usedProducts = ref<Record<string, ProductElementModel>>({});

    const loader = useEntityDataloader<ProductElementModel>({
        fetchByIds: productsLoader,
        getStore: () => usedProducts.value,
        setStore: (entities) => {
            Object.assign(usedProducts.value, entities);
        },
        getId: (item) => item.ID,
    });

    registerScopeQuery(
        STORE_NAME,
        "app",
        {
            catalog_product_all: {
                __fragment: "ProductElementFields",
            },
            catalog_product_section_list: {
                __fragment: "ProductSectionFields",
            },
        },
        (data) => {
            fillProducts(data.catalog_product_all)
            sections.value = data.catalog_product_section_list
        }
    );

    return {
        loader,
        usedProducts,
        products,
        productsById,
        productsModelsById,
        productsModelsBySection,
        sections,
        sectionsById,
        sectionsByCode,
        sectionsTree,
        productsBySection,
        fillProduct,
        fillProducts,
        ensureProduct,
        ensureProducts,
        ensureProductsById
    };
});


export interface ProductBenefitModel extends ProductBenefit {
    product?: Maybe<Product>
    productModel?: Maybe<ProductElementModel>
}

export interface ProductSetItemModel extends ProductSetItem {
    product?: Maybe<Product>
    productModel?: Maybe<ProductElementModel>
}

export interface ProductSchedule {
    weekdays: Record<string, ProductScheduleWeekday>;
    items: ProductScheduleItem[];
    titles: string[];
}

export interface ProductScheduleWeekday {
    hours: string;
    from: string;
    to: string;
    startMinutes: number;
    endMinutes: number;
}

export interface ProductScheduleItem {
    dayCode: string;
    dayLabel: string;
    dayWeekIndex: number;
    timeFrom: string;
    timeTo: string;
    timeHours: string;
}

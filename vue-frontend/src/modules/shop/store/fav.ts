import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

const STORE_NAME = 'shop-fav'

export const useShopFavStore = defineStore(STORE_NAME, () => {

    const {registerScopeQuery} = useScopeQuery()
    const catalogStore = useCatalogStore()

    const favProductIds = ref<Record<string, number>>({})
    const favorites = ref<ProductElementModel[]>([])

    // ScopeQuery: загружаем избранные товары при инициализации приложения
    registerScopeQuery(STORE_NAME, 'user', {
        catalog_product_fav_list: {
            __fragment: 'ProductElementFields'
        },
    }, (data) => {

    })

    const favToggle = async (productId: number) => {
        if (favProductIds.value[productId]) {
            delete favProductIds.value[productId]
            // Удаляем из списка избранных
            favorites.value = favorites.value.filter(product => product.ID !== productId)
        } else {
            favProductIds.value[productId] = productId
            // Добавляем в список избранных
            const product = await catalogStore.ensureProduct(productId)
            if (product && !favorites.value.find(p => p.ID === productId)) {
                favorites.value.push(product)
            }
        }

        /*
        const res = await mutationWrapped(
            salePubFavAddMutation({
                state: {
                    __fragment: 'ResponseState'
                }
            }, {
                productId: productId
            })
        )
         */
    }

    const isInFavorites = (productId: number) => {
        return !!favProductIds.value[productId]
    }

    const favoritesCount = computed(() => Object.keys(favProductIds.value).length)

    return {
        favProductIds,
        favorites,
        favToggle,
        isInFavorites,
        favoritesCount
    }
})


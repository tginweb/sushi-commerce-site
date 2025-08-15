import {Ref} from "vue";
import {Product, ProductSection} from "@/gql/gen";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";
import {ID, Maybe} from "@/core/types";

export type CatalogStoreGettersLoader = () => {
    products: Ref<Product[]>
    productsById: Ref<Record<string, Product>>
    productsModelsById: Ref<Record<string, ProductElementModel>>
    sections: Ref<ProductSection[]>
    resolveProduct: (product: ID | Product) => Maybe<Product>
    resolveProductModel: (product: ID | Product | ProductElementModel) => Maybe<ProductElementModel>
}

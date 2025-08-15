           

import {ProductElement, AppProductRecordset, QueryCatalogProductRecordsetArgs} from "src/gql/gen/schema";

import Query from '../query/catalogProductRecordsetQuery';

import {useEntityRecordsetQueryGenerator} from "@/core/graphql/hooks/useEntityRecordsetQuery";

export default useEntityRecordsetQueryGenerator<ProductElement, {
    catalog_product_recordset: AppProductRecordset
},  QueryCatalogProductRecordsetArgs>({
    queryBuilder: Query,
    queryAlias: 'catalog_product_recordset'
})
      
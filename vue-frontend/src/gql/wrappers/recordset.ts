import EntityQueryInfoChunk from "@gen-types/json-fragments/EntityQueryInfoChunk"
import QueryTableChunk from "@gen-types/json-fragments/QueryTableChunk"

export default function qwRecordset(doc: any) {
    return {
        data: doc,
        meta: EntityQueryInfoChunk,
        table: QueryTableChunk
    }
}

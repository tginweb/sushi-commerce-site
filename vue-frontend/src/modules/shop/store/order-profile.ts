import {OrderProfile,} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore} from "pinia";
import {computed, toRefs} from "vue";
import {useGraphql} from "@/core/graphql/service";
import saleProfileSaveMutation from "@/gql/gen/mutation/saleProfileSaveMutation";
import {OrderProfilePayload, useShopStateStore} from "@/modules/shop/store/state";
import {OrderProfileModel, useProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";
import saleProfileDeleteMutation from "@/gql/gen/mutation/saleProfileDeleteMutation";

const STORE_NAME = "shop-order-profile";

export const useOrderProfileStore = defineStore(STORE_NAME, () => {
    const {registerScopeQuery} = useScopeQuery();
    const state = useShopStateStore()

    const {
        profiles,
        profilesPayload,
    } = toRefs(state)

    const {useMutation, responseSelection} = useGraphql();

    const profileById = computed(() =>
        profiles.value.reduce<Record<string, OrderProfile>>(
            (map, item) => (
                (map[item.ID || ''] = item as unknown as OrderProfile), map
            ),
            {}
        )
    );

    const profilesModels = computed(() =>
        profiles.value.map(item => useProfileModel(item))
    )

    const profilesModelsById = computed(() => profilesModels.value.reduce<Record<string, OrderProfileModel>>((map, item) => (map[item.id] = item, map), {}))

    const setProfilePayload = (profileId: string, payload: OrderProfilePayload) => {
        profilesPayload.value[profileId] = {
            ...(profilesPayload.value[profileId] || {}),
            ...payload
        }
    }

    const profileSaveRequest = useMutation(saleProfileSaveMutation({
        payload: {
            __fragment: 'OrderProfileFields'
        },
        ...responseSelection()
    }))

    const profileDeleteRequest = useMutation(saleProfileDeleteMutation({
        ...responseSelection()
    }))


    const profileSave = async (profile: OrderProfile, apiSave = true) => {

        let savedProfile = null

        if (apiSave) {
            const res = await profileSaveRequest.mutate({
                id: profile.ID,
                attrs: profile.ATTR
            })
            savedProfile = res?.payload
        } else {
            savedProfile = profile
        }

        if (savedProfile) {
            const index = profiles.value.findIndex(item => item.ID === savedProfile.ID)
            if (index > -1) {
                profiles.value[index] = savedProfile
            } else {
                profiles.value.push(savedProfile)
            }
            return savedProfile
        }
        return false
    };

    const profileDelete = async (profileId: number) => {
        const {success} = await profileDeleteRequest.mutate({
            id: profileId,
        })
        if (success) {
            const index = profiles.value.findIndex(item => item.ID === profileId)
            if (index > -1)
                profiles.value.splice(index, 1)
        }
    };


    registerScopeQuery(
        STORE_NAME,
        "user",
        {
            sale_profile_list: {
                __fragment: "OrderProfileFields",
            },
        },
        (data) => {
            if (data.sale_profile_list)
                profiles.value = data.sale_profile_list.map(item => item)
        }
    );

    return {
        setProfilePayload,
        profileById,
        profilesModels,
        profilesModelsById,
        profileSaveRequest,
        profileDeleteRequest,
        profileSave,
        profileDelete,
    };
});


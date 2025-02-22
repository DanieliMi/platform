import Vue from 'vue';
import type { Module } from 'vuex';

interface SdkLocationState {
    locations: {
        [locationId: string]: string
    }
}

/**
 * This store contains Vue components for locations as a fallback when no iFrame should get rendered
 */
const SdkLocationStore: Module<SdkLocationState, VuexRootState> = {
    namespaced: true,

    state: (): SdkLocationState => ({
        locations: {},
    }),

    mutations: {
        addLocation(state, { locationId, componentName }: { locationId: string, componentName: string }) {
            if (!state.locations[locationId]) {
                Vue.set(state.locations, locationId, componentName);
            }
        },
    },
};

export default SdkLocationStore;
export type { SdkLocationState };

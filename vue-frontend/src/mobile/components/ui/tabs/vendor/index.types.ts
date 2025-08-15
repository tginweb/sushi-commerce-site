import React from 'react';
import TabBar from './TabBar';
import {TabControllerBarProps} from './TabBar.types';
import TabBarItem from './TabBarItem';
import type {TabControllerItemProps} from './TabBarItem.types';
import TabPage from './TabPage';
import PageCarousel from './PageCarousel';
import {TabControllerImperativeMethods} from './useImperativeTabControllerHandle.types';

export type {TabControllerBarProps, TabControllerItemProps, TabControllerImperativeMethods};

interface TabControllerStatics {
    TabBar: typeof TabBar;
    TabBarItem: typeof TabBarItem;
    TabPage: typeof TabPage;
    PageCarousel: typeof PageCarousel;
}

export interface TabControllerProps {
    /**
     * The list of tab bar items
     */
    items: TabControllerItemProps[];
    /**
     * Initial selected index
     */
    initialIndex?: number;
    /**
     * callback for when index has change (will not be called on ignored items)
     */
    onChangeIndex?: (index: number, prevIndex: number | null, fromExternal: boolean) => void;

    /**
     * callback for when index has change (will not be called on ignored items)
     */
    onBeforeChangeIndex?: (index: number, prevIndex: number | null, fromExternal: boolean) => void;

    /**
     * When using TabController.PageCarousel this should be turned on
     */
    asCarousel?: boolean;
    /**
     * Pass for custom carousel page width
     */
    carouselPageWidth?: number;
    /**
     * Send if a SafeView is used in the context of the TabController.
     */
    useSafeArea?: boolean;
    children?: React.ReactNode;
}

declare const _default: React.ForwardRefExoticComponent<TabControllerProps & {
    children?: React.ReactNode;
} & React.RefAttributes<any>> & TabControllerStatics;
export default _default;

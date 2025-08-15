import React, { Component } from 'react';
import { StyleSheet, ScrollView, View, Platform } from 'react-native';
import PropTypes from "prop-types";
import {LinearGradient} from "expo-linear-gradient"
const defaultFadeColors = ['rgba(255, 255, 255, 0.2)', 'rgba(255, 255, 255, 0.4)', 'rgba(255, 255, 255, 1)'];

export default class RNFadedScrollView extends Component {

    constructor(props) {
        super(props);
        this.state = {
            // We don't know the size of the content initially, and the probably won't instantly try to scroll,
            // so set the initial content height and width to 0
            scrollHeight: 0,
            scrollWidth: 0,
            availableWidth: 0,
            availableHeight: 0,
            allowStartFade: false,
            allowEndFade: true
        }
    }

    onContentSizeChange = (contentWidth, contentHeight) => {
        // Save the content height in state
        this.setState({ scrollHeight: contentHeight, scrollWidth: contentWidth });
    };

    _onLayout(event) {
        const containerWidth = event.nativeEvent.layout.width;
        const containerHeight = event.nativeEvent.layout.height;

        this.setState({ availableWidth: containerWidth, availableHeight: containerHeight })
    }

    isEndFadeAllowed() {
        const sizeToCompare = this.props.horizontal ? this.state.scrollWidth : this.state.scrollHeight;
        const availableSpace = this.props.horizontal ? this.state.availableWidth : this.state.availableHeight;
        return this.props.allowEndFade ? sizeToCompare > availableSpace : false;
    }

    ifCloseToStart({ layoutMeasurement, contentOffset, contentSize }) {
        return this.props.horizontal ? contentOffset.x < this.props.scrollThreshold : contentOffset.y < this.props.scrollThreshold;
    }
    isCloseToBottom({ layoutMeasurement, contentOffset, contentSize }) {
        return this.props.horizontal ? layoutMeasurement.width + contentOffset.x >= contentSize.width - this.props.scrollThreshold : layoutMeasurement.height + contentOffset.y >= contentSize.height - this.props.scrollThreshold;
    }

    //To avoid ScrollView RTL issue on andorid.
    allowReverse() {
        return Platform.OS == 'android' && this.props.isRtl
    }
    onScrolled = (e) => {
        if (this.props.isCloseToEnd) {
            this.props.isCloseToEnd(this.isCloseToBottom(e.nativeEvent));
        }
        if (this.props.isCloseToStart) {
            this.props.isCloseToStart(this.ifCloseToStart(e.nativeEvent));
        }
        if (this.props.allowStartFade) {
            if (!this.allowReverse()) {
                this.setState({ allowStartFade: this.ifCloseToStart(e.nativeEvent) ? false : true })
            }
            else {
                this.setState({ allowEndFade: this.ifCloseToStart(e.nativeEvent) ? false : true })
            }
        }
        if (this.props.allowEndFade) {
            if (!this.allowReverse()) {
                this.setState({ allowEndFade: this.isCloseToBottom(e.nativeEvent) ? false : true })
            }
            else {
                this.setState({ allowStartFade: this.isCloseToBottom(e.nativeEvent) ? false : true })
            }
        }
        if (this.props.onScroll) {
            this.props.onScroll();
        }
    }

    //get start fade view
    getStartFaade() {
        return (this.props.horizontal ?
                <LinearGradient
                    start={{ x: this.props.isRtl ? 0 : 1, y: 0 }} end={{ x: this.props.isRtl ? 1 : 0, y: 0 }}
                    style={[{ position: 'absolute', start: 0, width: this.props.fadeSize, height: '100%' }, this.props.startFadeStyle]}
                    colors={this.props.fadeColors}
                    pointerEvents={'none'}
                /> :
                <LinearGradient
                    start={{ x: 0, y: 1 }} end={{ x: 0, y: 0 }}
                    style={[{ position: 'absolute', top: 0, width: '100%', height: this.props.fadeSize }, this.props.startFadeStyle]}
                    colors={this.props.fadeColors}
                    pointerEvents={'none'}
                />
        )
    }

    getEndFade() {
        return (this.props.horizontal ?
            <LinearGradient
                start={{ x: this.props.isRtl ? 1 : 0, y: 0 }} end={{ x: this.props.isRtl ? 0 : 1, y: 0 }}
                style={[{ position: 'absolute', end: 0, width: this.props.fadeSize, height: '100%' }, this.props.endFadeStyle]}
                colors={this.props.fadeColors}
                pointerEvents={'none'}
            />
            :
            <LinearGradient
                start={{ x: 0, y: 0 }} end={{ x: 0, y: 1 }}
                style={[{ position: 'absolute', bottom: 0, width: '100%', height: this.props.fadeSize }, this.props.endFadeStyle]}
                colors={this.props.fadeColors}
                pointerEvents={'none'}
            />)
    }
    getDivider() {
        return (this.props.horizontal ? <View
            style={[{ width: 1, height: '100%', backgroundColor: "#E6E6E6" }, this.props.dividerStyle]}
        /> : <View
            style={[{ width: '100%', height: 1, backgroundColor: "#E6E6E6" }, this.props.dividerStyle]}
        />)
    }

    render() {
        const endFadeEnable = this.isEndFadeAllowed();
        return (
            <View style={this.props.containerStyle || [styles.container,  { flexDirection: this.props.horizontal ? "row" : "column" }]}
                  onLayout={this._onLayout.bind(this)}>
                {(this.state.allowStartFade && this.props.allowDivider) && this.getDivider()}
                <ScrollView
                    {...this.props}
                    style={[styles.scrollViewStyle, this.props.style]}
                    onContentSizeChange={this.onContentSizeChange}
                    scrollEventThrottle={16}
                    onScroll={this.onScrolled}
                >
                    {this.props.children}
                </ScrollView>
                {((endFadeEnable && this.state.allowEndFade) && this.props.allowDivider) && this.getDivider()}
                {(this.state.allowStartFade) && this.getStartFaade()}
                {(endFadeEnable && this.state.allowEndFade) && this.getEndFade()}
            </View>
        )
    }
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        flexDirection: "column"
    },
    scrollViewStyle: {
        flex: 1
    }
});
RNFadedScrollView.propTypes = {
    allowStartFade: PropTypes.bool,
    allowEndFade: PropTypes.bool,
    fadeSize: PropTypes.number,
    fadeColors: PropTypes.array,
    isCloseToEnd: PropTypes.func,
    isCloseToStart: PropTypes.func,
    scrollThreshold: PropTypes.number,
    allowDivider: PropTypes.bool,
    isRtl: PropTypes.bool
}


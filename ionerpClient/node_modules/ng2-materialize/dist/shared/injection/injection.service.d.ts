import { ApplicationRef, ComponentFactoryResolver, ComponentRef, Injector, Type } from '@angular/core';
export declare class MzInjectionService {
    private applicationRef;
    private componentFactoryResolver;
    private injector;
    private container;
    constructor(applicationRef: ApplicationRef, componentFactoryResolver: ComponentFactoryResolver, injector: Injector);
    /**
     * Appends a component to an adjacent location.
     *
     * @template T
     * @param {Type<T>} componentClass
     * @param {*} [options={}]
     * @param {Element} [location=this.getRootViewContainerNode()]
     * @returns {ComponentRef<T>}
     *
     * @memberOf MzInjectionService
     */
    appendComponent<T>(componentClass: Type<T>, options?: any, location?: Element): ComponentRef<T>;
    /**
     * Overrides the default root view container.
     *
     * @param {any} container
     *
     * @memberOf MzInjectionService
     */
    setRootViewContainer(container: any): void;
    /**
     * Gets the root view container to inject the component to.
     *
     * @template T
     * @returns {ComponentRef<T>}
     *
     * @memberOf MzInjectionService
     */
    private getRootViewContainer<T>();
    /**
     * Gets the html element for a component ref.
     *
     * @param {ComponentRef<any>} componentRef
     * @returns {HTMLElement}
     *
     * @memberOf MzInjectionService
     */
    private getComponentRootNode(componentRef);
    /**
     * Gets the root component container html element.
     *
     * @returns {HTMLElement}
     *
     * @memberOf MzInjectionService
     */
    private getRootViewContainerNode();
    /**
     * Projects the inputs onto the component.
     *
     * @param {ComponentRef<any>} component
     * @param {*} options
     * @returns {ComponentRef<any>}
     *
     * @memberOf MzInjectionService
     */
    private projectComponentInputs<T>(component, options);
}

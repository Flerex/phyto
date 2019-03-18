import React, {Component} from 'react'

export default class TreeView extends Component {


    constructor(props) {
        super(props)
    }

    render() {
        return (
            <div>
                <ul>
                    {this.props.data.map((el, i) => <Node key={i} element={el}
                                                          appendList={this.props.appendList}/>)}
                </ul>
            </div>
        )
    }
}


/**
 * Component representing a node of the tree.
 *
 * This component will also render the node's children recursively.
 */
class Node extends Component {

    constructor(props) {
        super(props)

        this.hasChildren = this.hasChildren.bind(this)
        this.toggle = this.toggle.bind(this)
        this.renderAppendList = this.renderAppendList.bind(this)

        this.state = {
            expanded: true,
        };
    }

    toggle() {
        this.setState({
            expanded: !this.state.expanded,
        })
    }

    render() {
        const el = this.props.element;

        return (
            <li>
                <div onClick={this.toggle}>
                    <span className="icon" hidden={!this.hasChildren()}><i
                        className={this.state.expanded ? 'fas fa-chevron-down' : 'fas fa-chevron-right'}/></span>
                    <span className={this.hasChildren() ? 'has-text-weight-bold' : ''}>{el.name}</span>
                </div>
                {this.renderChildren()}
            </li>
        )
    }


    hasChildren() {
        return this.props.element.hasOwnProperty('children') && this.props.element.children.length > 0;
    }

    renderChildren() {

        if (!this.hasChildren())
            return '';

        return (
            <ul hidden={!this.state.expanded}>
                {this.props.element.children.map((el, i) =>
                    <Node key={i} element={el} appendList={this.props.appendList}/>
                )}
                {this.renderAppendList()}
            </ul>
        )
    }

    renderAppendList() {
        if (!this.props.appendList)
            return

        return this.props.appendList(this.props.element)
    }
}


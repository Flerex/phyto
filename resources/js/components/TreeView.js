import React, {Component} from 'react'
import styles from '../../sass/components/TreeView.scss'

export default class TreeView extends Component {


    constructor(props) {
        super(props)

        this.renderAppendList = this.renderAppendList.bind(this)
    }

    renderAppendList() {
        if (!this.props.appendList)
            return

        return this.props.appendList(null)
    }

    render() {
        return (
            <div className={styles.treeview}>
                <ul>
                    {this.props.data.map((el, i) => <Node key={i} element={el}
                                                          appendList={this.props.appendList}
                                                          appendNode={this.props.appendNode}
                                                          prependNode={this.props.prependNode}/>)}
                    {this.renderAppendList()}
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
        this.renderAppendNode = this.renderAppendNode.bind(this)
        this.renderAppendList = this.renderAppendList.bind(this)
        this.renderPrependNode = this.renderPrependNode.bind(this)
        this.renderChildren = this.renderChildren.bind(this)

        this.state = {
            expanded: true,
        };
    }

    toggle() {
        this.setState({
            expanded: !this.state.expanded,
        })
    }

    renderAppendList() {
        if (!this.props.appendList)
            return

        return this.props.appendList(this.props.element)
    }

    render() {
        const el = this.props.element;

        return (
            <li>
                <div>
                    {this.renderPrependNode()}

                    <span onClick={this.toggle} className={styles.toggler}>
                        <span className="icon" hidden={!this.hasChildren()}><i
                            className={this.state.expanded ? 'fas fa-chevron-down' : 'fas fa-chevron-right'}/></span>
                        <span className={this.hasChildren() ? 'has-text-weight-bold' : ''}>{el.name}</span>
                    </span>
                    {this.renderAppendNode()}
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
                    <Node key={i} element={el} appendList={this.props.appendList}
                          appendNode={this.props.appendNode} prependNode={this.props.prependNode} />
                )}
                {this.renderAppendList()}
            </ul>
        )
    }

    renderAppendNode() {
        if (!this.props.appendNode)
            return

        return this.props.appendNode(this.props.element)
    }

    renderPrependNode() {
        if (!this.props.prependNode)
            return

        return this.props.prependNode(this.props.element)
    }
}


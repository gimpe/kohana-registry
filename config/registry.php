<?php

/**
 * Configuration file for kohana-registry module
 *
 * 
 * Config format:
 *
 * return array(
 *     // id used to retrive the object
 *     'id' => array(
 *         // object to return for the id
 *         'class' => 'NameSpace\Class',
 *         // attributes to set via constructor (in order)
 *         'constructor_attributes' => array(
 *             0 => 'value1',
 *             1 => 'value2',
 *             ),
 *         // attributes to set via public attributes or setter method
  *         'setter_attributes' => array(
 *             'attribute1' => 'value1',
 *             'attribute2' => 'value2',
 *             ),
 *         // instanciate only on first use (default: TRUE) or instanciate during module init (FALSE)
 *         'use_lazyload' => TRUE,
 *         // use a single instance (default: TRUE) or create new instances each time (FALSE)
 *         'use_singleton' => TRUE,
 *         ),
 *     );
 * 
 * Other exemples available at https://github.com/gimpe/kohana-registry/wiki
 *
 * 
 * LICENSE: THE WORK (AS DEFINED BELOW) IS PROVIDED UNDER THE TERMS OF THIS
 * CREATIVE COMMONS PUBLIC LICENSE ("CCPL" OR "LICENSE"). THE WORK IS PROTECTED
 * BY COPYRIGHT AND/OR OTHER APPLICABLE LAW. ANY USE OF THE WORK OTHER THAN AS
 * AUTHORIZED UNDER THIS LICENSE OR COPYRIGHT LAW IS PROHIBITED.
 *
 * BY EXERCISING ANY RIGHTS TO THE WORK PROVIDED HERE, YOU ACCEPT AND AGREE TO
 * BE BOUND BY THE TERMS OF THIS LICENSE. TO THE EXTENT THIS LICENSE MAY BE
 * CONSIDERED TO BE A CONTRACT, THE LICENSOR GRANTS YOU THE RIGHTS CONTAINED HERE
 * IN CONSIDERATION OF YOUR ACCEPTANCE OF SUCH TERMS AND CONDITIONS.
 *
 * @category  module
 * @package   kohana-registry
 * @author    gimpe <gimpehub@intljaywalkers.com>
 * @copyright 2011 International Jaywalkers
 * @license   http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 * @link      http://github.com/gimpe/kohana-registry
 */
return array(
    );
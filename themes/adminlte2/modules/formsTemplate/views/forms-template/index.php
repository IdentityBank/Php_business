<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use yii\helpers\Html;

$frontAssets = FrontAsset::register($this);
$frontAssets->loadEditor();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Editor')) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div id="editor">
                            <table style="width:100%" class="table table-bordered">
                                <tr>
                                    <th><?= Translate::_('business', 'Field name') ?></th>
                                    <th><?= Translate::_('business', 'Type') ?></th>
                                    <th><?= Translate::_('business', 'Description') ?></th>
                                    <th><?= Translate::_('business', 'Remove') ?></th>
                                </tr>
                                <tr v-for="(row, index) in rows">
                                    <td><input v-bind:style="inputStyle" type="text" v-model="row.name"></td>
                                    <td>
                                        <select v-bind:style="inputStyle">
                                            <option v-for="type in types" v-bind:value="type.value">
                                                {{ type.value }}
                                            </option>
                                        </select>
                                    </td>
                                    <td><input v-bind:style="inputStyle" type="text" v-model="row.description"></td>
                                    <td v-bind:style="centerBtnRow">
                                        <a v-on:click="removeElement(index);" class="btn btn-app-trash"
                                           style="cursor: pointer"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                </tr>
                            </table>
                            <div v-bind:style="centerBtnRow">
                                <button class="btn btn-primary" @click="addRow">Add row</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
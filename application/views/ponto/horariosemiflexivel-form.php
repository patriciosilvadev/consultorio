<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_voltar">
        <a href="<?= base_url() ?>ponto/horariostipo">
            Voltar
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro de Horario Fixo</a></h3>
        <div>
            <form name="form_cargo" id="form_servidor" action="<?= base_url() ?>ponto/horariostipo/gravarhorariosemiflexivel" method="post">

                <dl class="dl_desconto_lista">
                    <dd>
                        <input type="hidden" id="txthorariostipoID" name="txthorariostipoID" value="<?= $horariostipo_id; ?>" />
                    </dd>
                </dl> 
                <table>
                    <tr>
                        <th>Inicio</th>
                        <th>Quantidade</th>
                        <th>Entrada padrao</th>
                        <th>Saida padrao</th>
                        <th>Entrada extra</th>
                        <th>Saida extra</th>
                        <th>Entrada extensao</th>
                        <th>saida extensao</th>
                    </tr>
                    <tr>
                        <td><input type="text"  id="txtinicio" name="txtinicio" class="size1" /></td>
                        <td><input type="text"  id="txtquantidade" name="txtquantidade" class="size1" /></td>
                        <td><input type="text"  id="txthoraEntrada1" name="txthoraEntrada1" alt="time" class="size1" /></td>
                        <td><input type="text"  id="txthoraSaida1" name="txthoraSaida1" alt="time" class="size1" /></td>
                        <td><input type="text"  id="txthoraEntrada2" name="txthoraEntrada2" alt="time" value="00:00" class="size1" /></td>
                        <td><input type="text"  id="txthoraSaida2" name="txthoraSaida2" alt="time" value="00:00" class="size1" /></td>
                        <td><input type="text"  id="txthoraEntrada3" name="txthoraEntrada3" alt="time" value="00:00" class="size1" /></td>
                        <td><input type="text"  id="txthoraSaida3" name="txthoraSaida3" alt="time" value="00:00" class="size1" /></td>
                    </tr>
                </table>    

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    
    $('#btnVoltar').click(function() {
        $(location).attr('href', '<?= base_url(); ?>ponto/cargo');
    });

    $(function() {
        $( "#accordion" ).accordion();
    });

    $(document).ready(function(){
        jQuery('#form_cargo').validate( {
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                }
            }
        });
    });

</script>
        <div class="footer_wrap ">


                <footer id="footer" class="container">
                    <div class="sub_footer">
                            
                        <p>
                             <?php esc_html_e( 'Design By: ', 'shophistic-lite' ); ?><a href="<?php echo esc_url( __( '#', 'shophistic-lite' ) ); ?>">
                             <?php esc_html_e( 'Brad Aytes', 'shophistic-lite' ); ?></a>. <?php printf( esc_html__( '© Lex TopDollar - LexTopDollar.com All Rights Reserved', 'shophistic-lite' ), 'Lex TopDollar - LexTopDollar.com', '<a href="http://www.lextopdollar.com/" rel="designer"> Lex TopDollar - LexTopDollar.com</a>' ); ?>                        
                        </p>

                        <?php get_template_part( '/templates/menu', 'social' ); ?>
                           
                        <div class="clearfix"></div>
                    </div><!-- /sub_footer -->
                </footer><!-- /footer -->


            <div class="clearfix"></div>
                
        </div><!-- /footer_wrap -->

        </div><!-- /wrap -->

    
        
    <!-- WP_Footer --> 
    <?php wp_footer(); ?>
    <!-- /WP_Footer --> 

      
    </body>
</html>
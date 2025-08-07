<?php
/**
 * Template Principal - Index
 */

get_header(); ?>

<main id="main" class="site-main">
  <div class="container">

    <?php if (have_posts()): ?>

      <div class="posts-list">
        <?php while (have_posts()):
          the_post(); ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
              <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
              </h2>

              <div class="entry-meta">
                <span class="posted-on">
                  <?php echo get_the_date(); ?>
                </span>
                <span class="byline">
                  por <?php the_author(); ?>
                </span>
              </div>
            </header>

            <div class="entry-content">
              <?php the_excerpt(); ?>
            </div>

            <footer class="entry-footer">
              <a href="<?php the_permalink(); ?>" class="read-more">
                Leia mais
              </a>
            </footer>
          </article>

        <?php endwhile; ?>
      </div>

      <?php
      // Navegação de posts
      the_posts_navigation(array(
        'prev_text' => 'Posts anteriores',
        'next_text' => 'Próximos posts',
      ));
      ?>

    <?php else: ?>

      <div class="no-posts">
        <h2>Nenhum conteúdo encontrado</h2>
        <p>Desculpe, mas nenhum conteúdo foi encontrado.</p>
      </div>

    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
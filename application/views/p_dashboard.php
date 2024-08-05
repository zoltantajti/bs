<section class="d-flex flex-nowrap h-100">
    <aside class="col-lg-2 d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
        <?php $this->load->view('_global/sidebar'); ?>
    </aside>
    <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
        <?php $this->load->view('crm/' . $m); ?>
    </main>
</section>
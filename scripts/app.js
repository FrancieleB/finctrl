
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('form').forEach(form=>{
      form.addEventListener('submit', e=>{
        const valorInput = form.querySelector('input[name="valor"]');
        if (valorInput) {
          const v = parseFloat(valorInput.value);
          if (isNaN(v) || v <= 0) {
            e.preventDefault();
            alert('Informe um valor maior que zero.');
          }
        }
      });
    });
  });
  
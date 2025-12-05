# 🧪 Teste Rápido - Minificação HTML

## Como Testar se está Funcionando

### 1️⃣ **Teste Visual no Navegador**

```bash
# Abra qualquer página do site
# Clique com botão direito → "Ver código-fonte" (Ctrl+U)

# ANTES da minificação:
# Você veria:
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        ...

# DEPOIS da minificação:
# Você verá:
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">...
```

### 2️⃣ **Teste com cURL**

```bash
# No terminal
cd /Users/rodrigo.vesilva/Documents/projectsphp/agenciaaids

# Baixar HTML e contar bytes
curl -s https://agenciaaids.com.br/ | wc -c

# Ou salvar em arquivo para análise
curl -s https://agenciaaids.com.br/ > output.html
cat output.html
```

### 3️⃣ **Verificar Preservação de Tags**

Acesse páginas que tenham:
- ✅ Formulários (`<textarea>`)
- ✅ Código formatado (se tiver `<pre>`)
- ✅ JavaScript inline
- ✅ CSS inline

**Todos devem funcionar normalmente!**

### 4️⃣ **DevTools Network Tab**

```
1. Abra DevTools (F12)
2. Aba "Network"
3. Recarregue a página (Ctrl+R)
4. Clique no primeiro documento
5. Veja "Size" → deve ser menor agora
```

---

## ✅ Checklist de Funcionamento

- [ ] HTML está em uma linha só (minificado)
- [ ] Página carrega normalmente
- [ ] Formulários funcionam
- [ ] JavaScript funciona
- [ ] CSS aplicado corretamente
- [ ] Não há console errors
- [ ] Tamanho do arquivo reduziu

---

## 🔧 Se algo der errado

### Desativar temporariamente:

```php
// Em functions.php, comente a linha:
// require_once get_template_directory() . '/inc/html-minify.php';
```

### Debug:

```php
// Adicione no início de html-minify.php:
error_log('HTML Minify executando...');

// Veja os logs em:
// wp-content/debug.log
```

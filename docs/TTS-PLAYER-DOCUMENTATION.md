# 🎙️ TTS Player - Text-to-Speech para WordPress

## 📋 Sobre

Sistema de **Text-to-Speech (TTS)** implementado para permitir que os visitantes do site ouçam as postagens através de um player de áudio integrado. Utiliza a **Web Speech API nativa do navegador**, sem dependências externas ou custos.

---

## ✅ Características

### 🎯 Funcionalidades
- ✅ **Player completo** com controles Play, Pause e Stop
- ✅ **Controle de velocidade** (0.5x a 2.0x)
- ✅ **Seleção de voz** (vozes disponíveis no navegador)
- ✅ **Barra de progresso** visual
- ✅ **Atalhos de teclado** (Espaço para play/pause, Esc para stop)
- ✅ **Interface responsiva** e acessível
- ✅ **Status em tempo real** do player
- ✅ **Totalmente gratuito** e open-source

### 🎨 Design
- Design moderno com gradientes
- Animações suaves
- Ícones SVG inline
- Feedback visual do estado (playing/paused)
- Suporte a dark mode
- Acessibilidade (ARIA labels, roles)

### 🚀 Performance
- Carregado apenas em páginas single
- JavaScript vanilla (sem jQuery)
- CSS modular
- Versioning automático baseado em filemtime

---

## 📁 Arquivos Criados

```
agenciaaids/
├── assets/
│   ├── js/
│   │   └── tts-player.js          # JavaScript do player
│   └── css/
│       └── components/
│           └── tts-player.css      # Estilos do player
├── inc/
│   └── tts-player.php              # Enfileiramento de scripts
├── partials/
│   └── tts-player.php              # Template HTML do player
└── functions.php                    # (modificado) Adicionado require
```

---

## 🔧 Como Funciona

### 1. **JavaScript (tts-player.js)**
- Detecta suporte do navegador para Web Speech API
- Extrai texto do elemento `.entry-content`
- Remove elementos não-textuais (imagens, scripts, etc.)
- Cria utterance com configurações personalizadas
- Gerencia estados (playing, paused, stopped)
- Atualiza UI em tempo real

### 2. **PHP (tts-player.php)**
- Enfileira scripts apenas em `is_single()`
- Adiciona versioning baseado em filemtime
- Carrega JS no footer para melhor performance

### 3. **Template (partials/tts-player.php)**
- HTML semântico com Schema.org
- Acessibilidade com ARIA
- SVG icons inline
- Estrutura modular

---

## 🎮 Uso

### Para o Usuário Final

1. **Iniciar reprodução**: Clique em "Reproduzir" ou pressione `Espaço`
2. **Pausar**: Clique em "Pausar" ou pressione `Espaço` novamente
3. **Parar**: Clique em "Parar" ou pressione `Esc`
4. **Ajustar velocidade**: Use o slider de velocidade (0.5x a 2.0x)
5. **Trocar voz**: Selecione uma voz diferente no dropdown

### Atalhos de Teclado
- `Espaço`: Play/Pause
- `Esc`: Stop

---

## 🌐 Compatibilidade de Navegadores

| Navegador | Versão Mínima | Suporte |
|-----------|---------------|---------|
| Chrome    | 33+           | ✅ Excelente |
| Edge      | 14+           | ✅ Excelente |
| Firefox   | 49+           | ⚠️ Bom (vozes limitadas) |
| Safari    | 14.1+         | ⚠️ Bom |
| Opera     | 21+           | ✅ Excelente |
| IE        | -             | ❌ Não suportado |

### Vozes Disponíveis por Navegador

- **Chrome/Edge**: ~20-50 vozes (incluindo pt-BR de alta qualidade)
- **Firefox**: ~10-15 vozes
- **Safari**: ~30 vozes (incluindo pt-BR)

---

## ⚙️ Configurações Técnicas

### Parâmetros do Speech Synthesis

```javascript
utterance.rate = 1.0;      // Velocidade (0.5 a 2.0)
utterance.pitch = 1;       // Tom de voz (fixo em 1)
utterance.volume = 1;      // Volume (máximo)
utterance.lang = 'pt-BR';  // Idioma
```

### Limites

- **Máximo de caracteres**: 5000 (para evitar sobrecarga)
- **Timeout**: Nenhum (controlado pelo navegador)
- **Conexão**: Funciona offline (vozes locais do navegador)

---

## 🎨 Personalização

### Cores do Player

Edite `/assets/css/components/tts-player.css`:

```css
/* Estado normal */
.tts-player {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Estado playing */
.tts-player.playing {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

/* Estado pausado */
.tts-player.paused {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
```

### Velocidades Disponíveis

Edite em `/partials/tts-player.php`:

```html
<input 
    type="range" 
    id="tts-speed" 
    min="0.5"     <!-- Mínimo -->
    max="2"       <!-- Máximo -->
    step="0.1"    <!-- Incremento -->
    value="1"     <!-- Padrão -->
>
```

---

## 🐛 Solução de Problemas

### Player não aparece?
1. Verifique se está em uma página single (`is_single()`)
2. Confirme que os arquivos foram criados corretamente
3. Limpe o cache do navegador
4. Verifique o console do navegador (F12)

### Sem vozes em português?
- **Chrome**: Instale vozes do sistema ou use extensões
- **Firefox**: Vozes são mais limitadas
- **Safari**: Vozes do macOS/iOS

### Player não funciona?
```javascript
// Abra console (F12) e verifique:
if ('speechSynthesis' in window) {
    console.log('TTS suportado!');
    console.log(window.speechSynthesis.getVoices());
} else {
    console.log('TTS não suportado');
}
```

### Performance lenta?
- O player limita a 5000 caracteres
- Texto muito longo pode ser cortado automaticamente

---

## 🔒 Segurança

✅ **Nenhum dado é enviado para servidores externos**
✅ **Processamento 100% no navegador do usuário**
✅ **Sem cookies ou tracking**
✅ **Sem requisição de permissões especiais**

---

## 📱 Acessibilidade

- ✅ **ARIA labels** em todos os controles
- ✅ **Roles semânticos** (region, button, progressbar)
- ✅ **Navegação por teclado** completa
- ✅ **Screen reader friendly**
- ✅ **Focus visible** para usuários de teclado
- ✅ **Suporte a prefers-reduced-motion**

---

## 🚀 Próximos Passos (Opcionais)

### Melhorias Futuras Possíveis

1. **Cache de Áudio** (experimental)
   - Salvar áudio gerado em IndexedDB
   - Reduz re-processamento

2. **Destacar Texto** durante leitura
   - Usar evento `onboundary`
   - Scroll automático

3. **Configurações Persistentes**
   - Salvar preferências no localStorage
   - Lembrar velocidade e voz escolhidas

4. **Estatísticas**
   - Rastrear uso do player
   - Analytics de engajamento

5. **Download de Áudio**
   - Exportar como MP3/WAV (requer API externa)

---

## 📖 Documentação Técnica

### Web Speech API
- [MDN - SpeechSynthesis](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis)
- [W3C Spec](https://wvvw.w3.org/TR/speech-synthesis/)

### WordPress
- [Enqueuing Scripts](https://developer.wordpress.org/reference/functions/wp_enqueue_script/)
- [Template Parts](https://developer.wordpress.org/reference/functions/get_template_part/)

---

## 📝 Changelog

### v1.0.0 (09/12/2025)
- ✅ Implementação inicial
- ✅ Player completo com todos os controles
- ✅ Design responsivo
- ✅ Acessibilidade implementada
- ✅ Atalhos de teclado
- ✅ Documentação completa

---

## 👨‍💻 Desenvolvedor

**Rodrigo Vieira Eufrasio da Silva**
- Site: [Agência AIDS](https://agenciaaids.com.br)

---

## 📄 Licença

Este código é parte do tema Agência AIDS.
Licença: GPL-2.0+

---

## 🙏 Créditos

- **Web Speech API**: Google Chrome Team / W3C
- **Ícones SVG**: Material Design Icons
- **Inspiração de Design**: Modern audio players

---

## 💡 Dicas

1. **Teste em diferentes navegadores** antes de publicar
2. **Informe aos usuários** sobre as limitações de vozes
3. **Considere adicionar um aviso** para navegadores não suportados
4. **Monitore feedback** dos usuários sobre qualidade de voz
5. **Teste com conteúdos longos** para verificar performance

---

## ❓ FAQ

**P: O player funciona offline?**
R: Sim! As vozes são locais do navegador.

**P: Tem custo?**
R: Não! É 100% gratuito.

**P: Funciona em mobile?**
R: Sim! Funciona em iOS e Android.

**P: Posso desativar em posts específicos?**
R: Sim, basta remover a linha `get_template_part('partials/tts-player')` do template.

**P: Como adicionar em outros post types?**
R: Adicione a mesma linha nos templates dos outros post types (ex: `single-videos.php`).

---

**Implementação concluída com sucesso! 🎉**

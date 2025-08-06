# 🕐 Sistema de Dumps Horários - Luvee WordPress

Sistema automatizado para criação de dumps do banco de dados remoto a cada hora, mantendo o ambiente local sempre sincronizável com dados atuais.

## 🎯 Funcionalidades

- ✅ **Dumps automáticos** de hora em hora
- ✅ **Conectividade direta** com banco remoto MySQL
- ✅ **Limpeza automática** de backups antigos (7 dias)
- ✅ **Logs detalhados** de todas as operações
- ✅ **Importação opcional** com atualização de URLs
- ✅ **Monitoramento** de status e estatísticas

## 📁 Scripts Disponíveis

| Script | Função | Uso |
|--------|--------|-----|
| `sync-hourly.sh` | Cria dump do banco remoto | Manual ou automático |
| `setup-hourly-sync.sh` | Configura cron job | Configuração inicial |
| `import-latest.sh` | Importa último dump | Quando precisar atualizar local |
| `monitor-dumps.sh` | Monitor de status | Verificar funcionamento |

## 🚀 Configuração Inicial

### 1. Configurar Automação

```bash
./setup-hourly-sync.sh
```

**Opções disponíveis:**
- **Opção 1**: Apenas dumps (recomendado)
- **Opção 2**: Dumps + importação automática  
- **Opção 3**: Remover automação
- **Opção 4**: Ver status atual

### 2. Primeira Execução Manual

```bash
# Criar primeiro dump
./sync-hourly.sh

# Verificar status
./monitor-dumps.sh
```

## 📊 Uso Diário

### Comandos Principais

```bash
# Ver status dos dumps
./monitor-dumps.sh

# Importar último dump no ambiente local  
./import-latest.sh

# Criar dump manual
./sync-hourly.sh

# Criar dump e importar automaticamente
./sync-hourly.sh --import
```

### Estrutura de Arquivos

```
database-backups/
├── hourly-dump-YYYYMMDD_HHMMSS.sql  # Dumps horários
├── latest-dump.sql                  # Link para último dump
├── sync.log                         # Log de sincronizações
├── cron.log                         # Log do cron job
└── local-before-*-YYYYMMDD_HHMMSS.sql  # Backups antes de importar
```

## ⚙️ Configurações

### Credenciais do Banco Remoto
```bash
Host: 108.167.168.18
Porta: 3306
Usuário: jeandr00_prd
Banco: jeandr00_wp167
URL: https://luveeparfum.com.br
```

### Configurações de Limpeza
- **Manter backups**: 7 dias
- **Máximo de arquivos**: 48 backups
- **Limpeza automática**: A cada execução

## 📈 Monitoramento

### Status do Sistema
```bash
./monitor-dumps.sh
```

**Informações mostradas:**
- Status do cron job
- Últimos backups criados
- Tamanho total dos arquivos
- Logs de execução
- Estatísticas de uso

### Logs Importantes

```bash
# Log de sincronizações
tail -f database-backups/sync.log

# Log do cron job (se configurado)
tail -f database-backups/cron.log
```

## 🔄 Fluxo de Trabalho Recomendado

### Para Desenvolvimento Diário:

1. **Configure uma vez:**
   ```bash
   ./setup-hourly-sync.sh  # Escolha opção 1 (apenas dumps)
   ```

2. **Quando precisar de dados atuais:**
   ```bash
   ./import-latest.sh      # Importa último dump disponível
   ```

3. **Monitore periodicamente:**
   ```bash
   ./monitor-dumps.sh      # Verifica se está funcionando
   ```

### Para Testes Específicos:

```bash
# Forçar novo dump do remoto agora
./sync-hourly.sh

# Importar imediatamente  
./sync-hourly.sh --import
```

## 🚨 Troubleshooting

### Problemas Comuns

**1. Erro de conectividade:**
```bash
# Testar conexão manual
mysql -h 108.167.168.18 -u jeandr00_prd -p jeandr00_wp167
```

**2. Cron não está funcionando:**
```bash
# Verificar cron jobs
crontab -l

# Reconfigurar
./setup-hourly-sync.sh
```

**3. Dumps muito antigos:**
```bash
# Forçar novo dump
./sync-hourly.sh

# Verificar logs
tail database-backups/sync.log
```

**4. Importação falhando:**
```bash
# Verificar containers
docker-compose ps

# Reiniciar se necessário
docker-compose restart mysql wordpress
```

## 🔧 Manutenção

### Limpeza Manual

```bash
# Remover backups antigos (mais de 7 dias)
find database-backups -name 'hourly-dump-*.sql' -mtime +7 -delete

# Limpar logs grandes
echo "" > database-backups/sync.log
echo "" > database-backups/cron.log
```

### Parar Automação

```bash
./setup-hourly-sync.sh  # Escolha opção 3
```

## 📋 Checklist de Configuração

- [ ] MySQL client instalado
- [ ] Credenciais do banco remoto configuradas
- [ ] Primeiro dump criado com sucesso
- [ ] Cron job configurado (opcional)
- [ ] Importação testada
- [ ] Monitoramento funcionando

## 🎯 Benefícios

- **Sempre atualizado**: Dumps a cada hora
- **Seguro**: Backups antes de importar
- **Automático**: Sem intervenção manual
- **Eficiente**: Limpeza automática
- **Monitorado**: Logs e estatísticas
- **Flexível**: Import manual quando necessário

---

**💡 Dica**: Use apenas dumps automáticos e importe manualmente quando precisar testar. Isso evita sobrescrever acidentalmente seu trabalho local.
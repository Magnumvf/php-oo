# Padrões de Projeto

Padrões da equipe: **Observer** e **Strategy**

------------

##### Equipe 5:
- Elias Leite
- Lucas Lima
- Magnum Freire
- Paulo Victor

------------

### Implementação do Observer

###### Problema:
Precisamos notificar os alunos da nossa escola sempre que novos cursos forem lançados. Essas notificações devem ser enviadas para o email e whatsapp do aluno.

###### Solução:

Criar um publicação de novos cursos que notifique todos os alunos quando um novo cursos for criado.

###### Implementação:

Criar um Publicador de novos cursos que implemente a interface `SplSubject`.

Arquivo: `\App\Observer\Publisher\NewCoursePublisher`

```php
class NewCoursePublisher implements SplSubject
{
    private SplObjectStorage $observers;

    public function __construct(
        private Curso $curso
    ) {
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
```

------------

Criar observadores que implementem a interface `SplObserver` para serem notificados quando um novo curso ser criado.

Arquivo: `\App\Observer\Listeners\CursoEmailObserver`

    class CursoEmailObserver implements SplObserver
    {
        private AlunoRepository $alunoRepository;
    
        public function __construct()
        {
            $this->alunoRepository = new AlunoRepository();
        }
    
        public function update(SplSubject $subject): void
        {
            $course = $subject->getCourseToNotify();
            $students = $this->alunoRepository->findAll();
    
            $msg = '';
            foreach ($students as $student) {
                $msg .= "Enviando email de notificação para o aluno: {$student->name}, sobre o novo curso: {$course->name}" . PHP_EOL;
            }
    
            $logWriter = new LogWriter($msg);
            $logWriter->execute();
        }
    }

Criar um caso de uso para implementar padrão.

Arquivo: `\App\UseCase\SendStudentNotificationAboutNewCourse`

    class SendStudentNotificationAboutNewCourse
    {
        private NewCoursePublisher $cursoPublisher;
        private CursoEmailObserver $cursoEmailObserver;
        private CursoWhatsappObserver $cursoWhatsappObserver;
    
        public function __construct(
            private Curso $curso
        ) {
            $this->cursoPublisher = new NewCoursePublisher($curso);
            $this->cursoEmailObserver = new CursoEmailObserver();
            $this->cursoWhatsappObserver = new CursoWhatsappObserver();
        }
    
        public function notify(): void
        {
            $this->cursoPublisher->attach($this->cursoEmailObserver);
            $this->cursoPublisher->attach($this->cursoWhatsappObserver);
            $this->cursoPublisher->notify();
        }
    }

Emitir o comportamento de notificar o aluno, no controlador de CursoController.

Arquivo: `\App\Controller\CursoController`

    final class CursoController extends AbstractController
    {
       ...
        public function add(): void
        {
            /**
             * Dispara notificação para os alunos
             */
            (new SendStudentNotificationAboutNewCourse($curso))->notify();
    
            parent::redirect("/cursos/listar");
        }
		...
    }
    
------------

### Implementação do Strategy

###### Problema:

Precisamos guardar que as notificações aos alunos foram de fato enviadas. Por hora gravaremos isso em um arquivo de log, mas posteriormente isso poderá ser salvo em um banco de dado dados.

###### Solução:

Criar uma estrategia que saiba como gravar logs em arquivo, mas que futuramente possa ser substituida por outra que saiba grava logs em banco de dados.

###### Implementação:

Criar um interface que irá definir o que é necessario para guardar a informação.

Arquivo: `\App\Strategy\LogStrategyInterface`

    interface LogStrategyInterface
    {
        public function write(string $message): void;
    }

Criar as estrategias de como salvar as informações de log.

Arquivo: `\App\Strategy\FileLog` [Estrategia em arquivo]

    class FileLog implements LogStrategyInterface
    {
        public function write(string $message): void
        {
            /**
             * Aqui tem a logica para gravar em arquivo
             */
    
            $log = '';
            $logLines = explode(PHP_EOL, $message);
            foreach ($logLines as $line) {
                if (empty($line)) {
                    continue;
                }
    
                $log .= "GRAVA LOG NO ARQUIVO {$line}" . PHP_EOL;
            }
    
            file_put_contents('envio.log', $log, FILE_APPEND | LOCK_EX);
        }
    }

Arquivo: `\App\Strategy\DataBaseLog` [Estrategia em DB]

    class DataBaseLog implements LogStrategyInterface
    {
    
        public function write(string $message): void
        {
            /**
             * Aqui tem a logica para gravar no Bando de Dados
             */
    
            $log = '';
            $logLines = explode(PHP_EOL, $message);
            foreach ($logLines as $line) {
                if (empty($line)) {
                    continue;
                }
    
                $log .= "GRAVA LOG NO BANCO {$line}" . PHP_EOL;
            }
    
            file_put_contents('envio.log', $log, FILE_APPEND | LOCK_EX);
        }
    }

Criar um caso de uso para implementar padrão.

Arquivo: `\App\UseCase\LogWriter`

    class LogWriter
    {
        protected LogStrategyInterface $strategy;
    
        public function __construct(
            protected string $message
        ) {
            $this->strategy = new App\Strategy\FileLog();
    //        $this->strategy = new App\Strategy\DataBaseLog();
        }
    
        public function execute(): void
        {
            $this->strategy->write($this->message);
        }
    }

Usar a estrategia ao enviar a notificação.

Arquivo: `\App\Observer\Listeners\CursoEmailObserver`

    class CursoEmailObserver implements SplObserver
    {
        ....
        public function update(SplSubject $subject): void
        {
            ...
            $logWriter = new \App\UseCase\LogWriter($msg);
            $logWriter->execute();
        }
    }
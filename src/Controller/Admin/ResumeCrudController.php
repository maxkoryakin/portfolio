<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Resume;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;

class ResumeCrudController extends AbstractCrudController
{
    public function __construct(private FileUploader $fileUploader)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Resume::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $resumeFileConstraints = [
            new File([
                'maxSize' => '5M',
                'mimeTypes' => ['application/pdf'],
                'mimeTypesMessage' => 'Please upload a valid PDF file',
            ]),
        ];

        return [
            TextField::new('titleEn')->setRequired(false),
            TextField::new('titleFr')->setRequired(false),
            TextField::new('filePathEn')
                ->setLabel('File (EN)')
                ->setTemplatePath('admin/fields/file_link.html.twig')
                ->onlyOnIndex(),
            TextField::new('filePathFr')
                ->setLabel('File (FR)')
                ->setTemplatePath('admin/fields/file_link.html.twig')
                ->onlyOnIndex(),
            TextField::new('resumeFileEn')
                ->setLabel('Upload PDF (EN)')
                ->setFormType(FileType::class)
                ->setFormTypeOption('mapped', false)
                ->setFormTypeOption('required', false)
                ->setFormTypeOption('constraints', $resumeFileConstraints)
                ->onlyOnForms(),
            TextField::new('resumeFileFr')
                ->setLabel('Upload PDF (FR)')
                ->setFormType(FileType::class)
                ->setFormTypeOption('mapped', false)
                ->setFormTypeOption('required', false)
                ->setFormTypeOption('constraints', $resumeFileConstraints)
                ->onlyOnForms(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Resume) {
            return;
        }

        $this->handleUpload($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Resume) {
            return;
        }

        $this->handleUpload($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    private function handleUpload(Resume $resume): void
    {
        $request = $this->getContext()->getRequest();
        $files = $request->files->get('Resume');

        if (!is_array($files)) {
            return;
        }

        $resumeFileEn = $files['resumeFileEn'] ?? null;
        if ($resumeFileEn instanceof UploadedFile) {
            $this->fileUploader->remove($resume->getFilePathEn(), 'resume');
            $resume->setFilePathEn($this->fileUploader->upload($resumeFileEn, 'resume'));
        }

        $resumeFileFr = $files['resumeFileFr'] ?? null;
        if ($resumeFileFr instanceof UploadedFile) {
            $this->fileUploader->remove($resume->getFilePathFr(), 'resume');
            $resume->setFilePathFr($this->fileUploader->upload($resumeFileFr, 'resume'));
        }
    }
}
